import React, { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/lib/api';
import { QuestLog, Quest, DifficultyType, FrequencyType, StatType } from '@/types';
import { supabase, isSupabaseConfigured } from '@/lib/supabase';
import { 
  ShieldAlert, 
  ShieldCheck,
  CheckCircle2, 
  XCircle, 
  Plus, 
  Trash2, 
  Clock, 
  User, 
  Coins, 
  Sparkles, 
  Eye, 
  Loader2,
  Zap,
  Shield,
  Swords,
  Flame,
  X,
  LayoutGrid,
  List,
  Search,
  Scroll,
  FileCheck2,
  PauseCircle,
  PlayCircle,
  ArrowRight,
  MessageSquareQuote
} from 'lucide-react';

const difficultyConfig: Record<DifficultyType, { label: string; color: string; bg: string; border: string; icon: React.ElementType }> = {
  easy: {
    label: 'Mudah',
    color: 'text-emerald-400',
    bg: 'bg-emerald-500/10',
    border: 'border-emerald-500/20',
    icon: Shield,
  },
  medium: {
    label: 'Sedang',
    color: 'text-amber-400',
    bg: 'bg-amber-500/10',
    border: 'border-amber-500/20',
    icon: Swords,
  },
  hard: {
    label: 'Sulit',
    color: 'text-rose-400',
    bg: 'bg-rose-500/10',
    border: 'border-rose-500/20',
    icon: Flame,
  },
};

const freqLabel: Record<string, string> = {
  daily: 'Harian',
  weekly: 'Mingguan',
  once: 'Sekali',
};

const containerVariants = {
  hidden: {},
  show: {
    transition: {
      staggerChildren: 0.05,
    },
  },
};

const cardVariants = {
  hidden: { opacity: 0, y: 12 },
  show: { opacity: 1, y: 0, transition: { duration: 0.3 } },
};

export const Admin: React.FC = () => {
  const { profile, isAdmin } = useAuth();
  const [submissions, setSubmissions] = useState<QuestLog[]>([]);
  const [adminQuests, setAdminQuests] = useState<Quest[]>([]);
  const [activeTab, setActiveTab] = useState<'submissions' | 'quests'>('quests');
  const [viewMode, setViewMode] = useState<'grid' | 'table'>('grid');
  const [searchQuery, setSearchQuery] = useState('');
  const [difficultyFilter, setDifficultyFilter] = useState<'all' | DifficultyType>('all');
  const [loading, setLoading] = useState(true);
  const [rejectingLogId, setRejectingLogId] = useState<number | null>(null);
  const [rejectNotes, setRejectNotes] = useState('');
  const [previewImage, setPreviewImage] = useState<string | null>(null);

  // New Admin Quest Form State
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [newTitle, setNewTitle] = useState('');
  const [newDesc, setNewDesc] = useState('');
  const [newDiff, setNewDiff] = useState<DifficultyType>('easy');
  const [newFreq, setNewFreq] = useState<FrequencyType>('daily');
  const [newExp, setNewExp] = useState(50);
  const [newGold, setNewGold] = useState(25);
  const [newStatType, setNewStatType] = useState<StatType | ''>('strength');
  const [newStatVal, setNewStatVal] = useState(2);

  const loadData = async () => {
    setLoading(true);
    try {
      const subs = await api.getAdminSubmissions();
      setSubmissions(subs);

      if (isSupabaseConfigured) {
        const { data: qData } = await supabase
          .from('quests')
          .select('*')
          .eq('is_admin_quest', true)
          .order('created_at', { ascending: false });
        setAdminQuests(qData || []);
      } else {
        setAdminQuests([
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
            title: 'Latihan Fisik (Push-up / Workout)',
            description: 'Lakukan olahraga ringan minimal 20 menit untuk menjaga stamina dan kebugaran tubuh.',
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
            id: 3,
            title: 'Membaca Buku Pengembangan Diri',
            description: 'Luangkan waktu 30 menit untuk membaca buku non-fiksi atau artikel mendalam.',
            difficulty: 'easy',
            frequency: 'daily',
            exp_reward: 40,
            gold_reward: 15,
            stat_reward_type: 'intelligence',
            stat_reward_value: 2,
            is_admin_quest: true,
            is_active: true,
            created_at: new Date().toISOString(),
          }
        ]);
      }
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  if (!isAdmin) {
    return (
      <div className="max-w-md mx-auto my-20 p-8 rounded-2xl bg-stone-900/60 border border-stone-800/70 backdrop-blur-sm text-center space-y-4">
        <div className="w-14 h-14 mx-auto rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center">
          <ShieldAlert className="w-7 h-7 text-rose-400" />
        </div>
        <h2 className="text-xl font-rpg font-semibold text-stone-50">Akses Terbatas</h2>
        <p className="text-xs text-stone-400 leading-relaxed">
          Halaman ini hanya dapat diakses oleh Akun Administrator. Silakan masuk menggunakan akun administrator.
        </p>
      </div>
    );
  }

  const handleApprove = async (logId: number) => {
    await api.approveSubmission(logId);
    loadData();
  };

  const handleReject = async (logId: number) => {
    await api.rejectSubmission(logId, rejectNotes || 'Bukti pengerjaan belum memenuhi kriteria.');
    setRejectingLogId(null);
    setRejectNotes('');
    loadData();
  };

  const handleToggleQuestStatus = async (questId: number, currentStatus: boolean) => {
    if (isSupabaseConfigured) {
      await supabase.from('quests').update({ is_active: !currentStatus }).eq('id', questId);
    } else {
      setAdminQuests(prev => prev.map(q => q.id === questId ? { ...q, is_active: !q.is_active } : q));
    }
    loadData();
  };

  const handleDeleteQuest = async (questId: number) => {
    if (window.confirm('Yakin ingin menghapus quest resmi ini dari sistem?')) {
      if (isSupabaseConfigured) {
        await supabase.from('quests').delete().eq('id', questId);
      } else {
        setAdminQuests(prev => prev.filter(q => q.id !== questId));
      }
      loadData();
    }
  };

  const handleCreateAdminQuest = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!newTitle.trim()) return;

    if (isSupabaseConfigured) {
      await supabase.from('quests').insert({
        title: newTitle,
        description: newDesc,
        difficulty: newDiff,
        frequency: newFreq,
        exp_reward: newExp,
        gold_reward: newGold,
        stat_reward_type: newStatType || null,
        stat_reward_value: newStatType ? newStatVal : 0,
        is_admin_quest: true,
        is_active: true,
      });
    } else {
      const created: Quest = {
        id: Date.now(),
        title: newTitle,
        description: newDesc,
        difficulty: newDiff,
        frequency: newFreq,
        exp_reward: newExp,
        gold_reward: newGold,
        stat_reward_type: newStatType || null,
        stat_reward_value: newStatType ? newStatVal : 0,
        is_admin_quest: true,
        is_active: true,
        created_at: new Date().toISOString(),
      };
      setAdminQuests(prev => [created, ...prev]);
    }

    setShowCreateModal(false);
    setNewTitle('');
    setNewDesc('');
    loadData();
  };

  // Filtered Quests
  const filteredQuests = adminQuests.filter((q) => {
    const matchesSearch = q.title.toLowerCase().includes(searchQuery.toLowerCase()) || 
      (q.description && q.description.toLowerCase().includes(searchQuery.toLowerCase()));
    const matchesDiff = difficultyFilter === 'all' || q.difficulty === difficultyFilter;
    return matchesSearch && matchesDiff;
  });

  const totalExpReward = adminQuests.reduce((acc, q) => acc + (q.exp_reward || 0), 0);
  const totalGoldReward = adminQuests.reduce((acc, q) => acc + (q.gold_reward || 0), 0);
  const activeCount = adminQuests.filter(q => q.is_active).length;

  return (
    <div className="w-full max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8 space-y-6">

      {/* ─── Hero / Header Banner ────────────────────────────────────────── */}
      <motion.div
        initial={{ opacity: 0, y: 12 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
        className="p-5 sm:p-6 rounded-2xl bg-stone-900/60 border border-stone-800/70 backdrop-blur-sm"
      >
        <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
          <div className="flex items-center gap-4">
            <div className="relative flex-shrink-0">
              <div className="w-14 h-14 rounded-2xl overflow-hidden border border-amber-500/30 bg-stone-800 shadow-gold flex items-center justify-center">
                <ShieldCheck className="w-7 h-7 text-amber-400" />
              </div>
              <div className="absolute -bottom-1 -right-1 px-1.5 py-0.2 rounded-md bg-rose-500 text-stone-950 text-[9px] font-bold uppercase tracking-wider">
                Admin
              </div>
            </div>
            <div>
              <div className="flex items-center gap-2">
                <span className="text-[11px] text-amber-400/90 font-mono font-semibold uppercase tracking-wider">
                  Sanctum Pengawas Guild
                </span>
                <span className="text-stone-700">·</span>
                <span className="flex items-center gap-1.5 text-[11px] text-emerald-400">
                  <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
                  <span>Sistem Siap</span>
                </span>
              </div>
              <h1 className="text-lg sm:text-xl font-rpg font-semibold text-stone-50 tracking-wide mt-0.5">
                Panel Pengawas & Kelola Quest
              </h1>
              <p className="text-xs text-stone-400 mt-1">
                Kurasi tantangan resmi komunitas dan verifikasi integritas bukti quest petualang.
              </p>
            </div>
          </div>

          {/* Quick Create Action */}
          <motion.button
            whileTap={{ scale: 0.97 }}
            onClick={() => setShowCreateModal(true)}
            className="flex-shrink-0 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 text-xs font-semibold shadow-gold-sm flex items-center gap-2 transition-colors"
          >
            <Plus className="w-4 h-4" />
            <span>Buat Quest Resmi</span>
          </motion.button>
        </div>
      </motion.div>

      {/* ─── Metric Summary Cards ────────────────────────────────────────── */}
      <motion.div
        variants={containerVariants}
        initial="hidden"
        animate="show"
        className="grid grid-cols-2 sm:grid-cols-4 gap-3.5"
      >
        <motion.div
          variants={cardVariants}
          className="p-4 rounded-xl bg-stone-900/50 border border-stone-800/60 space-y-2.5"
        >
          <div className="flex items-center justify-between">
            <span className="text-[11px] text-stone-400">Antrean Review</span>
            <div className="w-6 h-6 rounded-lg border flex items-center justify-center bg-amber-500/10 border-amber-500/20">
              <Clock className="w-3.5 h-3.5 text-amber-400" />
            </div>
          </div>
          <div className="text-xl font-bold font-mono text-amber-400">
            {submissions.length}
          </div>
          <p className="text-[10px] text-stone-500">Menunggu verifikasi bukti</p>
        </motion.div>

        <motion.div
          variants={cardVariants}
          className="p-4 rounded-xl bg-stone-900/50 border border-stone-800/60 space-y-2.5"
        >
          <div className="flex items-center justify-between">
            <span className="text-[11px] text-stone-400">Quest Resmi Aktif</span>
            <div className="w-6 h-6 rounded-lg border flex items-center justify-center bg-emerald-500/10 border-emerald-500/20">
              <Swords className="w-3.5 h-3.5 text-emerald-400" />
            </div>
          </div>
          <div className="text-xl font-bold font-mono text-emerald-400">
            {activeCount} <span className="text-xs text-stone-500 font-normal">/ {adminQuests.length}</span>
          </div>
          <p className="text-[10px] text-stone-500">Tersedia di papan quest</p>
        </motion.div>

        <motion.div
          variants={cardVariants}
          className="p-4 rounded-xl bg-stone-900/50 border border-stone-800/60 space-y-2.5"
        >
          <div className="flex items-center justify-between">
            <span className="text-[11px] text-stone-400">Total EXP Hadiah</span>
            <div className="w-6 h-6 rounded-lg border flex items-center justify-center bg-sky-500/10 border-sky-500/20">
              <Zap className="w-3.5 h-3.5 text-sky-400" />
            </div>
          </div>
          <div className="text-xl font-bold font-mono text-sky-400">
            +{totalExpReward}
          </div>
          <p className="text-[10px] text-stone-500">Potensi XP per putaran</p>
        </motion.div>

        <motion.div
          variants={cardVariants}
          className="p-4 rounded-xl bg-stone-900/50 border border-stone-800/60 space-y-2.5"
        >
          <div className="flex items-center justify-between">
            <span className="text-[11px] text-stone-400">Total Kas Reward</span>
            <div className="w-6 h-6 rounded-lg border flex items-center justify-center bg-amber-500/10 border-amber-500/20">
              <Coins className="w-3.5 h-3.5 text-amber-400" />
            </div>
          </div>
          <div className="text-xl font-bold font-mono text-amber-400">
            {totalGoldReward} G
          </div>
          <p className="text-[10px] text-stone-500">Koin emas resmi sistem</p>
        </motion.div>
      </motion.div>

      {/* ─── Segmented Navigation Bar ───────────────────────────────────── */}
      <motion.div
        initial={{ opacity: 0, y: 8 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.35, delay: 0.1 }}
        className="flex items-center gap-1.5 p-1 rounded-xl bg-stone-900/60 border border-stone-800/60 backdrop-blur-sm"
      >
        <button
          onClick={() => setActiveTab('quests')}
          className={`flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium transition-all duration-150 ${
            activeTab === 'quests'
              ? 'bg-amber-500/15 border border-amber-500/30 text-amber-300 shadow-sm font-semibold'
              : 'text-stone-400 hover:text-stone-200 hover:bg-stone-800/50 border border-transparent'
          }`}
        >
          <Scroll className="w-3.5 h-3.5" />
          <span>Katalog Quest Resmi</span>
          <span
            className={`text-[10px] px-1.5 py-0.5 rounded-md font-mono transition-colors ${
              activeTab === 'quests'
                ? 'bg-amber-500/25 text-amber-300 border border-amber-500/30'
                : 'bg-stone-800/80 text-stone-500 border border-stone-700/50'
            }`}
          >
            {adminQuests.length}
          </span>
        </button>

        <button
          onClick={() => setActiveTab('submissions')}
          className={`flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium transition-all duration-150 ${
            activeTab === 'submissions'
              ? 'bg-amber-500/15 border border-amber-500/30 text-amber-300 shadow-sm font-semibold'
              : 'text-stone-400 hover:text-stone-200 hover:bg-stone-800/50 border border-transparent'
          }`}
        >
          <FileCheck2 className="w-3.5 h-3.5" />
          <span>Review Bukti Pemain</span>
          <span
            className={`text-[10px] px-1.5 py-0.5 rounded-md font-mono transition-colors ${
              activeTab === 'submissions'
                ? 'bg-amber-500/25 text-amber-300 border border-amber-500/30'
                : 'bg-stone-800/80 text-stone-500 border border-stone-700/50'
            }`}
          >
            {submissions.length}
          </span>
        </button>
      </motion.div>

      {/* ─── Main Content Area ──────────────────────────────────────────── */}
      {loading ? (
        <div className="py-20 flex flex-col items-center justify-center gap-3 text-stone-500">
          <Loader2 className="w-6 h-6 animate-spin text-amber-500/60" />
          <span className="text-xs font-rpg tracking-wider uppercase">Memuat data admin...</span>
        </div>
      ) : (
        <>
          {/* ═══════════════════════════════════════════════════════════════ */}
          {/* TAB 1: KATALOG QUEST RESMI                                      */}
          {/* ═══════════════════════════════════════════════════════════════ */}
          {activeTab === 'quests' && (
            <div className="space-y-4">
              {/* Toolbar: Search, Filters & View Mode Toggle */}
              <div className="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 p-3 rounded-xl bg-stone-900/40 border border-stone-800/60 backdrop-blur-sm">
                <div className="flex items-center gap-2.5 flex-1 max-w-md">
                  <div className="relative flex-1">
                    <Search className="w-3.5 h-3.5 text-stone-500 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input
                      type="text"
                      value={searchQuery}
                      onChange={(e) => setSearchQuery(e.target.value)}
                      placeholder="Cari judul quest atau deskripsi..."
                      className="w-full bg-stone-950/70 border border-stone-800/80 rounded-lg pl-8 pr-3 py-1.5 text-xs text-stone-200 placeholder:text-stone-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
                    />
                  </div>
                  <select
                    value={difficultyFilter}
                    onChange={(e) => setDifficultyFilter(e.target.value as any)}
                    className="bg-stone-950/70 border border-stone-800/80 rounded-lg px-2.5 py-1.5 text-xs text-stone-300 focus:outline-none focus:border-amber-500/50 transition-all"
                  >
                    <option value="all" className="bg-stone-950">Semua Kesulitan</option>
                    <option value="easy" className="bg-stone-950">Mudah</option>
                    <option value="medium" className="bg-stone-950">Sedang</option>
                    <option value="hard" className="bg-stone-950">Sulit</option>
                  </select>
                </div>

                <div className="flex items-center justify-between sm:justify-end gap-3">
                  <span className="text-[11px] text-stone-500 font-mono">
                    {filteredQuests.length} quest ditampilkan
                  </span>
                  {/* View Mode Toggle */}
                  <div className="flex items-center gap-1 p-0.5 rounded-lg bg-stone-950/60 border border-stone-800/70">
                    <button
                      onClick={() => setViewMode('grid')}
                      title="Tampilan Kartu"
                      className={`p-1.5 rounded-md transition-colors ${
                        viewMode === 'grid'
                          ? 'bg-amber-500/20 text-amber-300'
                          : 'text-stone-500 hover:text-stone-300'
                      }`}
                    >
                      <LayoutGrid className="w-3.5 h-3.5" />
                    </button>
                    <button
                      onClick={() => setViewMode('table')}
                      title="Tampilan Tabel"
                      className={`p-1.5 rounded-md transition-colors ${
                        viewMode === 'table'
                          ? 'bg-amber-500/20 text-amber-300'
                          : 'text-stone-500 hover:text-stone-300'
                      }`}
                    >
                      <List className="w-3.5 h-3.5" />
                    </button>
                  </div>
                </div>
              </div>

              {/* Quests Content */}
              {filteredQuests.length === 0 ? (
                <div className="py-16 px-4 text-center rounded-2xl bg-stone-900/40 border border-stone-800/60 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                  <div className="w-10 h-10 rounded-xl bg-stone-800/60 border border-stone-700/60 flex items-center justify-center text-stone-400">
                    <Scroll className="w-5 h-5 text-amber-400/80" />
                  </div>
                  <p className="text-xs text-stone-400 max-w-sm mt-1">
                    Tidak ada quest resmi yang sesuai dengan filter pencarian saat ini.
                  </p>
                </div>
              ) : viewMode === 'grid' ? (
                /* ─── Card Grid View ─── */
                <motion.div
                  variants={containerVariants}
                  initial="hidden"
                  animate="show"
                  className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                  {filteredQuests.map((quest) => {
                    const diff = difficultyConfig[quest.difficulty] || difficultyConfig.easy;
                    const DiffIcon = diff.icon;
                    return (
                      <motion.div
                        key={quest.id}
                        variants={cardVariants}
                        whileHover={{ y: -2, transition: { duration: 0.15 } }}
                        className="group p-5 rounded-2xl bg-stone-900/50 border border-stone-800/60 hover:border-amber-500/30 backdrop-blur-sm flex flex-col justify-between transition-all duration-200 hover:shadow-gold-sm"
                      >
                        <div>
                          {/* Badges row */}
                          <div className="flex items-center justify-between gap-2 mb-3">
                            <div className="flex items-center gap-1.5">
                              <div className={`flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium border ${diff.color} ${diff.bg} ${diff.border}`}>
                                <DiffIcon className="w-3 h-3" />
                                <span>{diff.label}</span>
                              </div>
                              <span className="px-2 py-0.5 rounded-md text-[11px] text-stone-400 bg-stone-900/80 border border-stone-800/70 font-mono">
                                {freqLabel[quest.frequency] || quest.frequency}
                              </span>
                            </div>

                            {/* Status badge */}
                            <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-mono font-medium border ${
                              quest.is_active
                                ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                                : 'bg-stone-800/60 text-stone-500 border-stone-700/60'
                            }`}>
                              <span className={`w-1.5 h-1.5 rounded-full ${quest.is_active ? 'bg-emerald-400 animate-pulse' : 'bg-stone-600'}`} />
                              <span>{quest.is_active ? 'Aktif' : 'Dijeda'}</span>
                            </span>
                          </div>

                          {/* Title */}
                          <h3 className="text-sm font-semibold text-stone-100 group-hover:text-amber-200 transition-colors duration-200 line-clamp-1 mb-1">
                            {quest.title}
                          </h3>

                          {/* Description */}
                          <p className="text-xs text-stone-400 line-clamp-2 leading-relaxed mb-4">
                            {quest.description || 'Tidak ada instruksi khusus untuk quest ini.'}
                          </p>

                          {/* Reward Row */}
                          <div className="flex items-center gap-1.5 pt-3 border-t border-stone-800/50 mb-4">
                            <div className="flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-500/10 border border-sky-500/20 text-[11px] font-mono text-sky-400 font-semibold">
                              <Zap className="w-3 h-3" />
                              <span>+{quest.exp_reward} XP</span>
                            </div>
                            <div className="flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/20 text-[11px] font-mono text-amber-400 font-semibold">
                              <Coins className="w-3 h-3" />
                              <span>+{quest.gold_reward} G</span>
                            </div>
                            {quest.stat_reward_type && quest.stat_reward_value > 0 && (
                              <span className="px-2 py-0.5 rounded-md bg-stone-800/80 text-stone-300 border border-stone-700/60 text-[11px] font-mono capitalize font-semibold">
                                +{quest.stat_reward_value} {quest.stat_reward_type.slice(0, 3).toUpperCase()}
                              </span>
                            )}
                          </div>
                        </div>

                        {/* Admin Action Buttons */}
                        <div className="flex items-center gap-2 pt-2 border-t border-stone-800/60">
                          <motion.button
                            whileTap={{ scale: 0.97 }}
                            onClick={() => handleToggleQuestStatus(quest.id, quest.is_active)}
                            className={`flex-1 py-1.5 px-3 rounded-lg text-xs font-medium border flex items-center justify-center gap-1.5 transition-colors ${
                              quest.is_active
                                ? 'bg-stone-800/80 hover:bg-stone-700/80 text-stone-300 border-stone-700/60 hover:text-amber-200'
                                : 'bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border-emerald-500/25'
                            }`}
                          >
                            {quest.is_active ? (
                              <>
                                <PauseCircle className="w-3.5 h-3.5 text-stone-400" />
                                <span>Jeda Quest</span>
                              </>
                            ) : (
                              <>
                                <PlayCircle className="w-3.5 h-3.5 text-emerald-400" />
                                <span>Aktifkan</span>
                              </>
                            )}
                          </motion.button>
                          <motion.button
                            whileTap={{ scale: 0.95 }}
                            onClick={() => handleDeleteQuest(quest.id)}
                            className="p-2 rounded-lg text-stone-500 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition-all"
                            title="Hapus Quest"
                          >
                            <Trash2 className="w-3.5 h-3.5" />
                          </motion.button>
                        </div>
                      </motion.div>
                    );
                  })}
                </motion.div>
              ) : (
                /* ─── Ledger Table View ─── */
                <div className="bg-stone-900/50 border border-stone-800/60 rounded-2xl overflow-hidden backdrop-blur-sm shadow-dark">
                  <div className="overflow-x-auto">
                    <table className="w-full text-left text-xs">
                      <thead className="bg-stone-950/80 border-b border-stone-800/70 text-[10px] font-semibold uppercase tracking-wider text-stone-400 font-mono">
                        <tr>
                          <th className="py-3 px-4">Tantangan Quest</th>
                          <th className="py-3 px-4">Kesulitan</th>
                          <th className="py-3 px-4">Frekuensi</th>
                          <th className="py-3 px-4">Hadiah Reward</th>
                          <th className="py-3 px-4">Status</th>
                          <th className="py-3 px-4 text-right">Aksi Admin</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-stone-800/50">
                        {filteredQuests.map((q) => {
                          const diff = difficultyConfig[q.difficulty] || difficultyConfig.easy;
                          const DiffIcon = diff.icon;
                          return (
                            <tr key={q.id} className="hover:bg-stone-800/30 transition-colors">
                              <td className="py-3.5 px-4">
                                <div className="font-semibold text-stone-100">{q.title}</div>
                                <div className="text-[11px] text-stone-400 line-clamp-1 max-w-sm mt-0.5">
                                  {q.description || 'Tidak ada instruksi khusus.'}
                                </div>
                              </td>
                              <td className="py-3.5 px-4">
                                <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium border ${diff.color} ${diff.bg} ${diff.border}`}>
                                  <DiffIcon className="w-3 h-3" />
                                  <span>{diff.label}</span>
                                </span>
                              </td>
                              <td className="py-3.5 px-4 text-stone-400 font-mono capitalize">
                                {freqLabel[q.frequency] || q.frequency}
                              </td>
                              <td className="py-3.5 px-4">
                                <div className="flex items-center gap-2 font-mono text-[11px] font-semibold">
                                  <span className="text-sky-400">+{q.exp_reward} XP</span>
                                  <span className="text-stone-600">·</span>
                                  <span className="text-amber-400">+{q.gold_reward} G</span>
                                </div>
                              </td>
                              <td className="py-3.5 px-4">
                                <span className={`inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-mono border ${
                                  q.is_active
                                    ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                                    : 'bg-stone-800/60 text-stone-500 border-stone-700/60'
                                }`}>
                                  <span className={`w-1.5 h-1.5 rounded-full ${q.is_active ? 'bg-emerald-400' : 'bg-stone-600'}`} />
                                  <span>{q.is_active ? 'Aktif' : 'Dijeda'}</span>
                                </span>
                              </td>
                              <td className="py-3.5 px-4 text-right">
                                <div className="flex items-center justify-end gap-1.5">
                                  <button
                                    onClick={() => handleToggleQuestStatus(q.id, q.is_active)}
                                    className="p-1.5 text-stone-400 hover:text-amber-300 hover:bg-stone-800 rounded-lg transition-colors"
                                    title={q.is_active ? 'Jeda Quest' : 'Aktifkan Quest'}
                                  >
                                    {q.is_active ? <PauseCircle className="w-4 h-4" /> : <PlayCircle className="w-4 h-4 text-emerald-400" />}
                                  </button>
                                  <button
                                    onClick={() => handleDeleteQuest(q.id)}
                                    className="p-1.5 text-stone-500 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors"
                                    title="Hapus Quest"
                                  >
                                    <Trash2 className="w-4 h-4" />
                                  </button>
                                </div>
                              </td>
                            </tr>
                          );
                        })}
                      </tbody>
                    </table>
                  </div>
                </div>
              )}
            </div>
          )}

          {/* ═══════════════════════════════════════════════════════════════ */}
          {/* TAB 2: REVIEW BUKTI SUBMISSION PEMAIN                           */}
          {/* ═══════════════════════════════════════════════════════════════ */}
          {activeTab === 'submissions' && (
            <div className="space-y-4">
              {submissions.length === 0 ? (
                <div className="py-16 px-4 text-center rounded-2xl bg-stone-900/40 border border-stone-800/60 backdrop-blur-sm flex flex-col items-center justify-center gap-2.5">
                  <div className="w-12 h-12 rounded-2xl bg-stone-800/60 border border-stone-700/60 flex items-center justify-center text-stone-400 shadow-inner">
                    <CheckCircle2 className="w-6 h-6 text-emerald-400" />
                  </div>
                  <div>
                    <h3 className="text-sm font-semibold text-stone-200">Semua Bukti Telah Diverifikasi</h3>
                    <p className="text-xs text-stone-500 max-w-sm mt-1">
                      Tidak ada antrean submission pengerjaan quest yang menunggu peninjauan saat ini.
                    </p>
                  </div>
                </div>
              ) : (
                <motion.div
                  variants={containerVariants}
                  initial="hidden"
                  animate="show"
                  className="grid grid-cols-1 md:grid-cols-2 gap-4"
                >
                  {submissions.map((sub) => (
                    <motion.div
                      key={sub.id}
                      variants={cardVariants}
                      className="p-5 rounded-2xl bg-stone-900/50 border border-stone-800/60 hover:border-amber-500/30 backdrop-blur-sm space-y-4 transition-all duration-200 shadow-sm"
                    >
                      {/* Player identity & Status */}
                      <div className="flex items-start justify-between gap-3">
                        <div className="flex items-center gap-3">
                          <div className="w-10 h-10 rounded-xl overflow-hidden border border-amber-500/20 bg-stone-800 flex-shrink-0">
                            <img
                              src={sub.user?.avatar_url || '/images/char.png'}
                              alt={sub.user?.name}
                              className="w-full h-full object-cover"
                              onError={(e) => { e.currentTarget.src = '/images/char.png'; }}
                            />
                          </div>
                          <div>
                            <div className="text-xs font-semibold text-stone-100 flex items-center gap-1.5">
                              <span>{sub.user?.name || 'Petualang'}</span>
                              <span className="text-[10px] px-1.5 py-0.2 rounded-md bg-stone-800 text-stone-400 font-mono">
                                Lv.{sub.user?.level || 1}
                              </span>
                            </div>
                            <div className="text-[11px] text-stone-500 font-mono">
                              {sub.user?.email}
                            </div>
                          </div>
                        </div>

                        <span className="text-[10px] px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/20 font-medium font-mono whitespace-nowrap">
                          Pending Review
                        </span>
                      </div>

                      {/* Quest target reference */}
                      <div className="p-3 rounded-xl bg-stone-950/50 border border-stone-800/60 space-y-1">
                        <div className="text-[10px] text-stone-500 uppercase tracking-wider font-semibold">
                          Tantangan yang Dikerjakan
                        </div>
                        <div className="text-xs font-semibold text-stone-200">
                          {sub.quest?.title}
                        </div>
                      </div>

                      {/* Notes from player */}
                      {sub.submission_notes && (
                        <div className="p-3 rounded-xl bg-stone-950/60 border border-stone-800/70 text-xs text-stone-300 leading-relaxed flex items-start gap-2">
                          <MessageSquareQuote className="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5" />
                          <div className="flex-1">
                            <span className="text-[11px] text-stone-500 font-medium block mb-0.5">Catatan Pemain:</span>
                            "{sub.submission_notes}"
                          </div>
                        </div>
                      )}

                      {/* File Proof Preview */}
                      {sub.submission_file_path && (
                        <div className="space-y-1.5">
                          <span className="text-[11px] text-stone-400 font-medium uppercase tracking-wider block">
                            Bukti Foto / Lampiran:
                          </span>
                          <div
                            onClick={() => setPreviewImage(sub.submission_file_path || null)}
                            className="relative group cursor-pointer border border-stone-800/80 rounded-xl overflow-hidden max-h-48 bg-stone-950 flex items-center justify-center"
                          >
                            <img
                              src={sub.submission_file_path}
                              alt="Bukti Quest"
                              className="w-full h-48 object-cover group-hover:scale-105 transition duration-300"
                              onError={(e) => {
                                e.currentTarget.style.display = 'none';
                              }}
                            />
                            <div className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-xs font-medium text-stone-100">
                              <Eye className="w-4 h-4 text-amber-400" />
                              <span>Klik untuk memperbesar gambar</span>
                            </div>
                          </div>
                        </div>
                      )}

                      {/* Reward to be given */}
                      <div className="p-2.5 rounded-xl bg-stone-950/40 border border-stone-800/70 flex items-center justify-between text-xs">
                        <span className="text-stone-500">Reward Disiapkan:</span>
                        <div className="flex items-center gap-2">
                          <span className="flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-500/10 border border-sky-500/20 text-sky-400 font-mono text-[11px] font-semibold">
                            <Zap className="w-3 h-3" />
                            +{sub.quest?.exp_reward} XP
                          </span>
                          <span className="flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-400 font-mono text-[11px] font-semibold">
                            <Coins className="w-3 h-3" />
                            +{sub.quest?.gold_reward} Gold
                          </span>
                          {sub.quest?.stat_reward_type && (
                            <span className="px-2 py-0.5 rounded-md bg-stone-800/80 border border-stone-700/60 text-stone-300 font-mono text-[11px] font-semibold capitalize">
                              +{sub.quest?.stat_reward_value} {sub.quest?.stat_reward_type}
                            </span>
                          )}
                        </div>
                      </div>

                      {/* Action controls */}
                      {rejectingLogId === sub.id ? (
                        <div className="space-y-2.5 pt-2 border-t border-stone-800/70">
                          <textarea
                            rows={2}
                            value={rejectNotes}
                            onChange={(e) => setRejectNotes(e.target.value)}
                            placeholder="Alasan penolakan (misal: gambar buram / bukti tidak sesuai instruksi)..."
                            className="w-full bg-stone-950 border border-stone-800/80 rounded-xl p-2.5 text-xs text-stone-100 placeholder:text-stone-600 focus:outline-none focus:border-rose-500/50 focus:ring-1 focus:ring-rose-500/20 transition-all"
                          />
                          <div className="flex items-center justify-end gap-2">
                            <button
                              onClick={() => setRejectingLogId(null)}
                              className="px-3 py-1.5 text-xs text-stone-400 hover:text-stone-200 transition-colors"
                            >
                              Batal
                            </button>
                            <button
                              onClick={() => handleReject(sub.id)}
                              className="px-4 py-1.5 rounded-xl bg-rose-500 hover:bg-rose-400 text-stone-950 text-xs font-semibold transition-colors"
                            >
                              Konfirmasi Tolak
                            </button>
                          </div>
                        </div>
                      ) : (
                        <div className="flex items-center gap-2.5 pt-2 border-t border-stone-800/70">
                          <motion.button
                            whileTap={{ scale: 0.97 }}
                            onClick={() => handleApprove(sub.id)}
                            className="flex-1 py-2 px-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-stone-950 text-xs font-semibold shadow-sm flex items-center justify-center gap-1.5 transition-colors"
                          >
                            <CheckCircle2 className="w-3.5 h-3.5" />
                            <span>Setujui (Approve)</span>
                          </motion.button>
                          <motion.button
                            whileTap={{ scale: 0.97 }}
                            onClick={() => setRejectingLogId(sub.id)}
                            className="py-2 px-3.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors"
                          >
                            <XCircle className="w-3.5 h-3.5" />
                            <span>Tolak</span>
                          </motion.button>
                        </div>
                      )}
                    </motion.div>
                  ))}
                </motion.div>
              )}
            </div>
          )}
        </>
      )}

      {/* ─── Modal Rancang Quest Resmi Baru ─────────────────────────────── */}
      {showCreateModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fade-in">
          <motion.div
            initial={{ opacity: 0, scale: 0.95, y: 10 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.95, y: 10 }}
            className="bg-stone-950/95 border border-stone-800/80 rounded-2xl max-w-2xl w-full p-6 shadow-dark-lg relative backdrop-blur-xl text-stone-100 max-h-[90vh] overflow-y-auto scrollbar-thin"
          >
            <button
              onClick={() => setShowCreateModal(false)}
              className="absolute top-4 right-4 p-2 text-stone-500 hover:text-stone-200 rounded-lg hover:bg-stone-800/60 transition-colors"
            >
              <X className="w-5 h-5" />
            </button>

            <div className="mb-5">
              <div className="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold mb-2">
                <Scroll className="w-3.5 h-3.5" />
                <span>Titah Sayembara Resmi</span>
              </div>
              <h2 className="text-xl font-rpg font-semibold text-stone-100 tracking-wide">
                Rancang Quest Resmi Sistem
              </h2>
              <p className="text-xs text-stone-400 mt-1">
                Tantangan ini akan langsung dipublikasikan ke papan quest komunitas untuk diambil oleh seluruh petualang.
              </p>
            </div>

            <form onSubmit={handleCreateAdminQuest} className="space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="sm:col-span-2">
                  <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                    Judul Tantangan *
                  </label>
                  <input
                    type="text"
                    required
                    value={newTitle}
                    onChange={(e) => setNewTitle(e.target.value)}
                    placeholder="Contoh: Lari Marathon 5KM, Selesaikan Kursus Python..."
                    className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2.5 text-xs text-stone-100 placeholder:text-stone-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
                  />
                </div>

                <div className="sm:col-span-2">
                  <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                    Deskripsi & Kriteria Penyelesaian
                  </label>
                  <textarea
                    rows={2}
                    value={newDesc}
                    onChange={(e) => setNewDesc(e.target.value)}
                    placeholder="Jelaskan instruksi pengerjaan serta bukti foto/tangkapan layar yang wajib diunggah..."
                    className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2 text-xs text-stone-100 placeholder:text-stone-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                    Tingkat Kesulitan
                  </label>
                  <select
                    value={newDiff}
                    onChange={(e) => setNewDiff(e.target.value as DifficultyType)}
                    className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2.5 text-xs text-stone-100 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
                  >
                    <option value="easy" className="bg-stone-950">Mudah (Easy)</option>
                    <option value="medium" className="bg-stone-950">Sedang (Medium)</option>
                    <option value="hard" className="bg-stone-950">Sulit (Hard)</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                    Frekuensi Pengerjaan
                  </label>
                  <select
                    value={newFreq}
                    onChange={(e) => setNewFreq(e.target.value as FrequencyType)}
                    className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2.5 text-xs text-stone-100 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
                  >
                    <option value="daily" className="bg-stone-950">Harian (Daily)</option>
                    <option value="weekly" className="bg-stone-950">Mingguan (Weekly)</option>
                    <option value="once" className="bg-stone-950">Sekali Jalan (Once)</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                    Hadiah EXP
                  </label>
                  <input
                    type="number"
                    value={newExp}
                    onChange={(e) => setNewExp(Number(e.target.value))}
                    className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2 text-xs text-stone-100 font-mono focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                    Hadiah Gold
                  </label>
                  <input
                    type="number"
                    value={newGold}
                    onChange={(e) => setNewGold(Number(e.target.value))}
                    className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2 text-xs text-stone-100 font-mono focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                    Atribut RPG
                  </label>
                  <select
                    value={newStatType}
                    onChange={(e) => setNewStatType(e.target.value as StatType | '')}
                    className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2.5 text-xs text-stone-100 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
                  >
                    <option value="" className="bg-stone-950">Tanpa Atribut Tambahan</option>
                    <option value="strength" className="bg-stone-950">Strength (Kekuatan)</option>
                    <option value="intelligence" className="bg-stone-950">Intelligence (Kecerdasan)</option>
                    <option value="stamina" className="bg-stone-950">Stamina (Daya Tahan)</option>
                    <option value="agility" className="bg-stone-950">Agility (Kelincahan)</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                    Poin Atribut (+Stat)
                  </label>
                  <input
                    type="number"
                    disabled={!newStatType}
                    value={newStatVal}
                    onChange={(e) => setNewStatVal(Number(e.target.value))}
                    className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2 text-xs text-stone-100 font-mono focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 disabled:opacity-40 transition-all"
                  />
                </div>
              </div>

              {/* Live Preview Card */}
              <div className="p-3.5 rounded-xl bg-stone-900/60 border border-stone-800/70 space-y-2">
                <span className="text-[10px] text-stone-500 font-mono uppercase tracking-wider block">
                  Pratinjau Tampilan Kartu Quest:
                </span>
                <div className="flex items-center justify-between text-xs">
                  <span className="font-semibold text-stone-200">
                    {newTitle.trim() || 'Judul Quest...'}
                  </span>
                  <div className="flex items-center gap-1.5">
                    <span className="px-2 py-0.5 rounded-md bg-sky-500/10 border border-sky-500/20 text-sky-400 font-mono text-[11px] font-semibold">
                      +{newExp} XP
                    </span>
                    <span className="px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-400 font-mono text-[11px] font-semibold">
                      +{newGold} G
                    </span>
                  </div>
                </div>
              </div>

              <div className="flex items-center justify-end gap-2.5 pt-3 border-t border-stone-800/70">
                <button
                  type="button"
                  onClick={() => setShowCreateModal(false)}
                  className="px-4 py-2 text-xs font-medium text-stone-400 hover:text-stone-200 transition-colors"
                >
                  Batal
                </button>
                <motion.button
                  whileTap={{ scale: 0.97 }}
                  type="submit"
                  disabled={!newTitle.trim()}
                  className="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 text-xs font-semibold shadow-gold-sm transition-colors disabled:opacity-50"
                >
                  Terbitkan Quest Resmi
                </motion.button>
              </div>
            </form>
          </motion.div>
        </div>
      )}

      {/* ─── Large Image Preview Modal ──────────────────────────────────── */}
      {previewImage && (
        <div
          onClick={() => setPreviewImage(null)}
          className="fixed inset-0 z-50 p-4 bg-black/90 backdrop-blur-md flex items-center justify-center cursor-pointer animate-fade-in"
        >
          <div className="relative">
            <button
              onClick={() => setPreviewImage(null)}
              className="absolute -top-10 right-0 p-1 text-stone-400 hover:text-stone-100 transition-colors"
            >
              <X className="w-6 h-6" />
            </button>
            <img
              src={previewImage}
              alt="Preview"
              className="max-h-[85vh] max-w-[90vw] rounded-2xl object-contain shadow-dark-lg border border-stone-800"
            />
          </div>
        </div>
      )}
    </div>
  );
};
