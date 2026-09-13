import { supabase, isSupabaseConfigured } from './supabase';
import { Quest, QuestLog, Profile, Achievement, calculateLevel } from '@/types';

const API_BASE = import.meta.env.VITE_API_URL || '';

export const api = {
  // 1. Dashboard
  getDashboard: async (userId: string) => {
    if (!isSupabaseConfigured) {
      // Demo mock data
      return {
        profile: {
          id: userId,
          name: 'Hero Player (Demo)',
          exp: 340,
          gold: 150,
          level: calculateLevel(340),
          intelligence: 18,
          strength: 24,
          stamina: 20,
          agility: 15,
        },
        totalQuests: 12,
        completedQuests: 8,
        totalXP: 340,
        achievements: 3,
        totalAchievements: 7,
        currentStreak: 4,
        recentActivities: [
          {
            id: 101,
            quest: { title: 'Membaca Buku 30 Menit', exp_reward: 30, gold_reward: 15 },
            status: 'completed',
            updated_at: new Date(Date.now() - 3600000).toISOString(),
          },
          {
            id: 102,
            quest: { title: 'Push-up 30x & Sit-up', exp_reward: 60, gold_reward: 25 },
            status: 'completed',
            updated_at: new Date(Date.now() - 86400000).toISOString(),
          }
        ],
      };
    }

    try {
      const res = await fetch(`${API_BASE}/api/dashboard?userId=${userId}`);
      if (res.ok) return await res.json();
    } catch (e) {
      console.warn('API error, falling back to direct Supabase client:', e);
    }

    // Direct Supabase Fallback
    const { data: profile } = await supabase.from('profiles').select('*').eq('id', userId).single();
    const { count: totalQuests } = await supabase.from('quest_logs').select('*', { count: 'exact', head: true }).eq('user_id', userId);
    const { count: completedQuests } = await supabase.from('quest_logs').select('*', { count: 'exact', head: true }).eq('user_id', userId).eq('status', 'completed');
    const { count: achievements } = await supabase.from('user_achievements').select('*', { count: 'exact', head: true }).eq('user_id', userId);
    const { count: totalAchievements } = await supabase.from('achievements').select('*', { count: 'exact', head: true });

    return {
      profile: profile ? { ...profile, level: calculateLevel(profile.exp) } : null,
      totalQuests: totalQuests || 0,
      completedQuests: completedQuests || 0,
      totalXP: profile?.exp || 0,
      achievements: achievements || 0,
      totalAchievements: totalAchievements || 0,
      currentStreak: 1,
      recentActivities: [],
    };
  },

  // 2. Quests
  getQuests: async (userId: string) => {
    if (!isSupabaseConfigured) {
      // Demo mock quests
      const mockAdminQuests: Quest[] = [
        {
          id: 1,
          title: 'Mulai Perjalananmu',
          description: 'Selesaikan aktivitas positif pertamamu dan upload bukti di LifeQuest.',
          difficulty: 'easy',
          frequency: 'once',
          exp_reward: 50,
          gold_reward: 20,
          stat_reward_type: null,
          stat_reward_value: 0,
          is_admin_quest: true,
          is_active: true,
          created_at: new Date().toISOString(),
        },
        {
          id: 2,
          title: 'Membaca Buku 30 Menit',
          description: 'Baca buku pengembangan diri atau ilmu pengetahuan minimal 30 menit.',
          difficulty: 'easy',
          frequency: 'daily',
          exp_reward: 30,
          gold_reward: 15,
          stat_reward_type: 'intelligence',
          stat_reward_value: 2,
          is_admin_quest: true,
          is_active: true,
          created_at: new Date().toISOString(),
        },
        {
          id: 3,
          title: 'Latihan Fisik (Push-up / Workout)',
          description: 'Lakukan olahraga ringan minimal 20 menit untuk stamina dan fisik.',
          difficulty: 'medium',
          frequency: 'daily',
          exp_reward: 60,
          gold_reward: 25,
          stat_reward_type: 'strength',
          stat_reward_value: 3,
          is_admin_quest: true,
          is_active: true,
          created_at: new Date().toISOString(),
        },
        {
          id: 4,
          title: 'Jogging / Lari Pagi 2KM',
          description: 'Lari pagi minimal 2 km di sekitar lingkungan atau treadmill.',
          difficulty: 'medium',
          frequency: 'weekly',
          exp_reward: 120,
          gold_reward: 50,
          stat_reward_type: 'agility',
          stat_reward_value: 4,
          is_admin_quest: true,
          is_active: true,
          created_at: new Date().toISOString(),
        }
      ];

      const mockPersonalQuests: Quest[] = [
        {
          id: 5,
          title: 'Review Catatan Kuliah / Kerja',
          description: 'Evaluasi to-do harian sebelum tidur.',
          difficulty: 'easy',
          frequency: 'daily',
          exp_reward: 10,
          gold_reward: 5,
          stat_reward_type: 'intelligence',
          stat_reward_value: 1,
          creator_id: userId,
          is_admin_quest: false,
          is_active: true,
          created_at: new Date().toISOString(),
        }
      ];

      const mockMyQuests: QuestLog[] = [
        {
          id: 11,
          user_id: userId,
          quest_id: 2,
          status: 'active',
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
          quest: mockAdminQuests[1],
        }
      ];

      const mockCompletedQuests: QuestLog[] = [
        {
          id: 10,
          user_id: userId,
          quest_id: 1,
          status: 'completed',
          completed_at: new Date().toISOString(),
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
          quest: mockAdminQuests[0],
        }
      ];

      return {
        myQuests: mockMyQuests,
        adminQuests: mockAdminQuests.filter(q => !mockMyQuests.some(m => m.quest_id === q.id)),
        personalQuests: mockPersonalQuests,
        completedQuests: mockCompletedQuests,
        myTemplates: mockPersonalQuests,
      };
    }

    // Supabase Live Queries
    // 1. My Quests (active, pending_review, rejected)
    const { data: myQuests } = await supabase
      .from('quest_logs')
      .select('*, quest:quests(*)')
      .eq('user_id', userId)
      .in('status', ['active', 'pending_review', 'rejected'])
      .order('updated_at', { ascending: false });

    // 2. Completed Quests
    const { data: completedQuests } = await supabase
      .from('quest_logs')
      .select('*, quest:quests(*)')
      .eq('user_id', userId)
      .eq('status', 'completed')
      .order('updated_at', { ascending: false });

    // 3. Admin Quests
    const { data: adminQuests } = await supabase
      .from('quests')
      .select('*, logs:quest_logs(*)')
      .eq('is_admin_quest', true)
      .eq('is_active', true)
      .order('created_at', { ascending: false });

    // 4. Personal Quests
    const { data: personalQuests } = await supabase
      .from('quests')
      .select('*, logs:quest_logs(*)')
      .eq('is_admin_quest', false)
      .eq('creator_id', userId)
      .order('created_at', { ascending: false });

    return {
      myQuests: (myQuests as QuestLog[]) || [],
      adminQuests: (adminQuests as Quest[]) || [],
      personalQuests: (personalQuests as Quest[]) || [],
      completedQuests: (completedQuests as QuestLog[]) || [],
      myTemplates: (personalQuests as Quest[]) || [],
    };
  },

  // Take Quest
  takeQuest: async (questId: number, userId: string) => {
    if (!isSupabaseConfigured) {
      return { success: true, message: 'Quest berhasil diambil (Demo Mode)!' };
    }
    const res = await fetch(`${API_BASE}/api/quests/${questId}/take`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ userId }),
    });
    return await res.json();
  },

  // Complete Personal Quest
  completePersonalQuest: async (logId: number, userId: string) => {
    if (!isSupabaseConfigured) {
      return { success: true, message: 'Quest selesai! +EXP dan +Gold didapatkan (Demo Mode).' };
    }
    const res = await fetch(`${API_BASE}/api/quests/logs/${logId}/complete`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ userId }),
    });
    return await res.json();
  },

  // Submit Admin Quest with Proof
  submitQuestProof: async (logId: number, userId: string, filePath: string, notes: string) => {
    if (!isSupabaseConfigured) {
      return { success: true, message: 'Bukti quest berhasil diajukan untuk review (Demo Mode)!' };
    }
    const res = await fetch(`${API_BASE}/api/quests/logs/${logId}/submit`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ userId, filePath, notes }),
    });
    return await res.json();
  },

  // Cancel Quest
  cancelQuest: async (logId: number, userId: string) => {
    if (!isSupabaseConfigured) {
      return { success: true, message: 'Quest dibatalkan (Demo Mode).' };
    }
    const res = await fetch(`${API_BASE}/api/quests/logs/${logId}/cancel?userId=${userId}`, {
      method: 'DELETE',
    });
    return await res.json();
  },

  // Upload proof file to Supabase Storage
  uploadProofFile: async (file: File, userId: string): Promise<string> => {
    if (!isSupabaseConfigured) {
      return URL.createObjectURL(file); // Demo local blob
    }

    const fileExt = file.name.split('.').pop();
    const fileName = `${userId}/${Date.now()}.${fileExt}`;
    const { error: uploadError } = await supabase.storage
      .from('submissions')
      .upload(fileName, file);

    if (uploadError) throw uploadError;

    const { data } = supabase.storage.from('submissions').getPublicUrl(fileName);
    return data.publicUrl;
  },

  // Leaderboard
  getLeaderboard: async (userId?: string) => {
    if (!isSupabaseConfigured) {
      return {
        topUsers: [
          { id: '1', name: 'Arthur Pendragon', exp: 4500, level: 7, quest_logs_count: 24 },
          { id: '2', name: 'Geralt of Rivia', exp: 3800, level: 6, quest_logs_count: 19 },
          { id: '3', name: 'Linus Torvalds', exp: 3200, level: 5, quest_logs_count: 16 },
          { id: '4', name: 'Hero Player (Demo)', exp: 340, level: 2, quest_logs_count: 8 },
        ],
        currentUserRank: 4,
        currentUserQuestCount: 8,
      };
    }

    const res = await fetch(`${API_BASE}/api/leaderboard?userId=${userId || ''}`);
    return await res.json();
  },

  // Achievements
  getAchievements: async (userId?: string) => {
    if (!isSupabaseConfigured) {
      return [
        { id: 1, key_name: 'first_quest_completed', title: 'Pejuang Hari Pertama', description: 'Selesaikan aktivitas pertamamu.', exp_reward: 50, gold_reward: 20, rarity: 'common', unlocked_at: new Date().toISOString() },
        { id: 2, key_name: 'daily_10_streak', title: 'Rajin Harian', description: 'Selesaikan 10 quest harian berturut-turut.', exp_reward: 200, gold_reward: 100, rarity: 'rare', unlocked_at: null },
        { id: 3, key_name: 'quest_master_25', title: 'Petualang Sejati', description: 'Selesaikan 25 quest unik.', exp_reward: 500, gold_reward: 250, rarity: 'epic', unlocked_at: null },
        { id: 4, key_name: 'intellect_1', title: 'Sang Intelektual (T1)', description: 'Capai total 50 poin Intelligence.', exp_reward: 200, gold_reward: 0, rarity: 'rare', unlocked_at: null },
      ];
    }

    const { data: allAchievements } = await supabase.from('achievements').select('*').order('id');
    const { data: userUnlocked } = userId 
      ? await supabase.from('user_achievements').select('*').eq('user_id', userId)
      : { data: [] };

    const unlockedMap = new Set((userUnlocked || []).map((u: any) => u.achievement_id));

    return (allAchievements || []).map((ach: any) => ({
      ...ach,
      unlocked_at: unlockedMap.has(ach.id) ? new Date().toISOString() : null,
    }));
  },

  // Admin Submissions
  getAdminSubmissions: async (): Promise<QuestLog[]> => {
    if (!isSupabaseConfigured) {
      return [
        {
          id: 88,
          user_id: 'demo-player-id',
          quest_id: 3,
          status: 'pending_review',
          submission_file_path: '/images/char.png',
          submission_notes: 'Sudah lari pagi 2KM dan pushup 20x. Ini tangkapan layar tracker lari saya.',
          date: new Date().toISOString().split('T')[0],
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
          quest: {
            id: 3,
            title: 'Latihan Fisik (Push-up / Workout)',
            difficulty: 'medium',
            frequency: 'daily',
            exp_reward: 60,
            gold_reward: 25,
            stat_reward_type: 'strength',
            stat_reward_value: 3,
            is_admin_quest: true,
            is_active: true,
            created_at: new Date().toISOString(),
          },
          user: {
            id: 'demo-player-id',
            name: 'Hero Player (Demo)',
            email: 'hero@lifequest.app',
            is_admin: false,
            exp: 340,
            gold: 150,
            intelligence: 18,
            strength: 24,
            stamina: 20,
            agility: 15,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString(),
          }
        }
      ];
    }

    const { data } = await supabase
      .from('quest_logs')
      .select('*, quest:quests(*), user:profiles(*)')
      .eq('status', 'pending_review')
      .order('created_at', { ascending: false });

    return (data as QuestLog[]) || [];
  },

  approveSubmission: async (logId: number) => {
    if (!isSupabaseConfigured) {
      return { success: true, message: 'Submission disetujui (Demo Mode)!' };
    }
    const res = await fetch(`${API_BASE}/api/admin/submissions/${logId}/approve`, { method: 'POST' });
    return await res.json();
  },

  rejectSubmission: async (logId: number, adminNotes: string) => {
    if (!isSupabaseConfigured) {
      return { success: true, message: 'Submission ditolak (Demo Mode).' };
    }
    const res = await fetch(`${API_BASE}/api/admin/submissions/${logId}/reject`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ adminNotes }),
    });
    return await res.json();
  }
};

