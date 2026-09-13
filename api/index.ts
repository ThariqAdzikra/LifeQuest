import { Hono } from 'hono';
import { cors } from 'hono/cors';
import { createClient } from '@supabase/supabase-js';
import { calculateLevel, calculateDefaultRewards } from '../src/types';

// Create Hono app
const app = new Hono().basePath('/api');

// CORS middleware
app.use('*', cors());

// Helper to get Supabase client with request authorization token
const getSupabase = (c: any) => {
  const authHeader = c.req.header('Authorization');
  const token = authHeader ? authHeader.replace('Bearer ', '') : null;
  
  const supabaseUrl = c.env?.VITE_SUPABASE_URL || process.env.VITE_SUPABASE_URL || 'https://placeholder.supabase.co';
  const supabaseKey = c.env?.SUPABASE_SERVICE_ROLE_KEY || process.env.SUPABASE_SERVICE_ROLE_KEY || c.env?.VITE_SUPABASE_ANON_KEY || process.env.VITE_SUPABASE_ANON_KEY || 'placeholder';

  return createClient(supabaseUrl, supabaseKey, {
    auth: {
      persistSession: false,
      autoRefreshToken: false,
    },
    global: {
      headers: token ? { Authorization: `Bearer ${token}` } : {},
    },
  });
};

// 1. Health check
app.get('/health', (c) => {
  return c.json({
    status: 'ok',
    app: 'LifeQuest Edge API',
    runtime: 'Cloudflare Pages & Vercel Edge compatible',
    time: new Date().toISOString(),
  });
});

// 2. Dashboard Aggregated Stats
app.get('/dashboard', async (c) => {
  const userId = c.req.query('userId');
  if (!userId) {
    return c.json({ error: 'userId is required' }, 400);
  }

  const supabase = getSupabase(c);

  // Profile
  const { data: profile } = await supabase
    .from('profiles')
    .select('*')
    .eq('id', userId)
    .single();

  // Total Quests taken
  const { count: totalQuests } = await supabase
    .from('quest_logs')
    .select('*', { count: 'exact', head: true })
    .eq('user_id', userId);

  // Completed Quests
  const { count: completedQuests } = await supabase
    .from('quest_logs')
    .select('*', { count: 'exact', head: true })
    .eq('user_id', userId)
    .eq('status', 'completed');

  // Total achievements unlocked
  const { count: unlockedAchievements } = await supabase
    .from('user_achievements')
    .select('*', { count: 'exact', head: true })
    .eq('user_id', userId);

  const { count: totalAchievements } = await supabase
    .from('achievements')
    .select('*', { count: 'exact', head: true });

  // Recent activities
  const { data: recentActivities } = await supabase
    .from('quest_logs')
    .select('*, quest:quests(*)')
    .eq('user_id', userId)
    .eq('status', 'completed')
    .order('updated_at', { ascending: false })
    .limit(5);

  const totalExp = profile?.exp || 0;
  const level = calculateLevel(totalExp);

  return c.json({
    profile: profile ? { ...profile, level } : null,
    totalQuests: totalQuests || 0,
    completedQuests: completedQuests || 0,
    totalXP: totalExp,
    achievements: unlockedAchievements || 0,
    totalAchievements: totalAchievements || 0,
    currentStreak: 1,
    recentActivities: recentActivities || [],
  });
});

// 3. Quest Taking & Action Routes
app.post('/quests/:id/take', async (c) => {
  const questId = Number(c.req.param('id'));
  const body = await c.req.json().catch(() => ({}));
  const userId = body.userId;

  if (!userId) return c.json({ error: 'userId is required' }, 400);

  const supabase = getSupabase(c);

  // Check if already active/pending
  const { data: existing } = await supabase
    .from('quest_logs')
    .select('*')
    .eq('user_id', userId)
    .eq('quest_id', questId)
    .in('status', ['active', 'pending_review', 'rejected'])
    .maybeSingle();

  if (existing) {
    return c.json({ error: 'Anda sudah mengambil quest ini!' }, 400);
  }

  // Insert quest log
  const { data, error } = await supabase
    .from('quest_logs')
    .insert({
      user_id: userId,
      quest_id: questId,
      status: 'active',
      date: new Date().toISOString().split('T')[0],
    })
    .select('*, quest:quests(*)')
    .single();

  if (error) return c.json({ error: error.message }, 500);

  return c.json({ success: true, message: 'Quest berhasil diambil!', data });
});

// Complete Personal Quest (No proof required)
app.post('/quests/logs/:logId/complete', async (c) => {
  const logId = Number(c.req.param('logId'));
  const body = await c.req.json().catch(() => ({}));
  const userId = body.userId;

  const supabase = getSupabase(c);

  const { data: log, error: logErr } = await supabase
    .from('quest_logs')
    .select('*, quest:quests(*)')
    .eq('id', logId)
    .eq('user_id', userId)
    .single();

  if (logErr || !log) return c.json({ error: 'Quest log tidak ditemukan' }, 404);
  if (log.status === 'completed') return c.json({ error: 'Quest ini sudah diselesaikan' }, 400);
  if (log.quest.is_admin_quest) {
    return c.json({ error: 'Quest admin harus dikirim (submit) dengan bukti, bukan diselesaikan langsung.' }, 400);
  }

  // Reward player
  const { data: profile } = await supabase.from('profiles').select('*').eq('id', userId).single();
  if (profile) {
    const newExp = (profile.exp || 0) + log.quest.exp_reward;
    const newGold = (profile.gold || 0) + log.quest.gold_reward;
    const updates: any = { exp: newExp, gold: newGold, updated_at: new Date().toISOString() };

    if (log.quest.stat_reward_type && log.quest.stat_reward_value > 0) {
      const statName = log.quest.stat_reward_type;
      updates[statName] = (profile[statName] || 0) + log.quest.stat_reward_value;
    }

    await supabase.from('profiles').update(updates).eq('id', userId);
  }

  // Update log status
  await supabase
    .from('quest_logs')
    .update({
      status: 'completed',
      completed_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    })
    .eq('id', logId);

  return c.json({
    success: true,
    message: `Quest "${log.quest.title}" selesai! Reward didapatkan!`,
  });
});

// Submit Admin Quest with Proof
app.post('/quests/logs/:logId/submit', async (c) => {
  const logId = Number(c.req.param('logId'));
  const body = await c.req.json();
  const { userId, filePath, notes } = body;

  if (!filePath) {
    return c.json({ error: 'File bukti quest wajib diunggah!' }, 400);
  }

  const supabase = getSupabase(c);

  const { data: log } = await supabase
    .from('quest_logs')
    .select('*, quest:quests(*)')
    .eq('id', logId)
    .eq('user_id', userId)
    .single();

  if (!log) return c.json({ error: 'Quest log tidak ditemukan' }, 404);

  const { error } = await supabase
    .from('quest_logs')
    .update({
      status: 'pending_review',
      submission_file_path: filePath,
      submission_notes: notes || '',
      updated_at: new Date().toISOString(),
    })
    .eq('id', logId);

  if (error) return c.json({ error: error.message }, 500);

  return c.json({ success: true, message: 'Bukti quest berhasil diajukan untuk review admin!' });
});

// Cancel Quest Log
app.delete('/quests/logs/:logId/cancel', async (c) => {
  const logId = Number(c.req.param('logId'));
  const userId = c.req.query('userId');

  const supabase = getSupabase(c);
  const { error } = await supabase
    .from('quest_logs')
    .delete()
    .eq('id', logId)
    .eq('user_id', userId);

  if (error) return c.json({ error: error.message }, 500);
  return c.json({ success: true, message: 'Quest dibatalkan.' });
});

// 4. Admin Submissions Review
app.post('/admin/submissions/:logId/approve', async (c) => {
  const logId = Number(c.req.param('logId'));
  const supabase = getSupabase(c);

  const { data: log } = await supabase
    .from('quest_logs')
    .select('*, quest:quests(*), user:profiles(*)')
    .eq('id', logId)
    .single();

  if (!log || log.status !== 'pending_review') {
    return c.json({ error: 'Submission tidak valid atau sudah diproses' }, 400);
  }

  const quest = log.quest;
  const user = log.user;

  // Reward User
  const newExp = (user.exp || 0) + quest.exp_reward;
  const newGold = (user.gold || 0) + quest.gold_reward;
  const userUpdates: any = { exp: newExp, gold: newGold, updated_at: new Date().toISOString() };

  if (quest.stat_reward_type && quest.stat_reward_value > 0) {
    userUpdates[quest.stat_reward_type] = (user[quest.stat_reward_type] || 0) + quest.stat_reward_value;
  }

  await supabase.from('profiles').update(userUpdates).eq('id', user.id);

  // If quest unlocks an achievement
  if (quest.achievement_id) {
    await supabase.from('user_achievements').upsert({
      user_id: user.id,
      achievement_id: quest.achievement_id,
      unlocked_at: new Date().toISOString(),
    });
  }

  // Complete log
  await supabase
    .from('quest_logs')
    .update({
      status: 'completed',
      completed_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    })
    .eq('id', logId);

  return c.json({ success: true, message: 'Submission disetujui! Reward dan Title telah diberikan.' });
});

app.post('/admin/submissions/:logId/reject', async (c) => {
  const logId = Number(c.req.param('logId'));
  const body = await c.req.json().catch(() => ({}));
  const adminNotes = body.adminNotes || 'Bukti belum memenuhi kriteria.';

  const supabase = getSupabase(c);

  const { error } = await supabase
    .from('quest_logs')
    .update({
      status: 'rejected',
      admin_notes: adminNotes,
      updated_at: new Date().toISOString(),
    })
    .eq('id', logId);

  if (error) return c.json({ error: error.message }, 500);
  return c.json({ success: true, message: 'Submission ditolak dan dikembalikan ke pemain.' });
});

// 5. Leaderboard with Tie-Breaker (Identical to Laravel LeaderboardController.php)
app.get('/leaderboard', async (c) => {
  const currentUserId = c.req.query('userId');
  const supabase = getSupabase(c);

  // Fetch non-admin users
  const { data: users, error } = await supabase
    .from('profiles')
    .select('*, quest_logs:quest_logs(id, status, quest:quests(is_admin_quest))')
    .eq('is_admin', false);

  if (error) return c.json({ error: error.message }, 500);

  // Calculate completed admin quests count for each user
  const rankedUsers = (users || []).map((u: any) => {
    const completedAdminQuests = (u.quest_logs || []).filter(
      (ql: any) => ql.status === 'completed' && ql.quest?.is_admin_quest
    ).length;
    return {
      id: u.id,
      name: u.name,
      avatar_url: u.avatar_url,
      level: calculateLevel(u.exp),
      exp: u.exp,
      gold: u.gold,
      quest_logs_count: completedAdminQuests,
    };
  });

  // Sort: 1st by quest_logs_count desc, 2nd tie-breaker: name asc
  rankedUsers.sort((a, b) => {
    if (b.quest_logs_count !== a.quest_logs_count) {
      return b.quest_logs_count - a.quest_logs_count;
    }
    return a.name.localeCompare(b.name);
  });

  const topUsers = rankedUsers.slice(0, 20);

  let currentUserRank = null;
  let currentUserQuestCount = 0;

  if (currentUserId) {
    const userIndex = rankedUsers.findIndex((u) => u.id === currentUserId);
    if (userIndex !== -1) {
      currentUserRank = userIndex + 1;
      currentUserQuestCount = rankedUsers[userIndex].quest_logs_count;
    }
  }

  return c.json({
    topUsers,
    currentUserRank,
    currentUserQuestCount,
  });
});

export default app;

