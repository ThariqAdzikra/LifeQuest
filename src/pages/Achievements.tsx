import React, { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/lib/api';
import { Achievement } from '@/types';
import { Lock, CheckCircle2, Loader2, Zap, Coins, Star, Trophy } from 'lucide-react';

const rarityConfig: Record<string, { label: string; color: string; bg: string; border: string; glow: string }> = {
  legendary: {
    label: 'Legendaris',
    color: 'text-amber-300',
    bg: 'bg-amber-500/8',
    border: 'border-amber-500/30',
    glow: 'shadow-gold-sm',
  },
  epic: {
    label: 'Epik',
    color: 'text-rose-300',
    bg: 'bg-rose-500/8',
    border: 'border-rose-500/25',
    glow: '',
  },
  rare: {
    label: 'Langka',
    color: 'text-sky-300',
    bg: 'bg-sky-500/8',
    border: 'border-sky-500/25',
    glow: '',
  },
  common: {
    label: 'Biasa',
    color: 'text-stone-400',
    bg: 'bg-stone-800/60',
    border: 'border-stone-700/60',
    glow: '',
  },
};

const achievementIcons = ['🏆', '⚔️', '🛡️', '🔥', '⭐', '💎', '🎯', '🌟', '🗡️', '📜'];

export const Achievements: React.FC = () => {
  const { profile } = useAuth();
  const [achievements, setAchievements] = useState<Achievement[]>([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState<'all' | 'unlocked' | 'locked'>('all');

  useEffect(() => {
    api.getAchievements(profile?.id).then((data) => {
      setAchievements(data);
      setLoading(false);
    });
  }, [profile?.id]);

  const unlockedCount = achievements.filter((a) => a.unlocked_at).length;
  const filtered = achievements.filter((a) => {
    if (filter === 'unlocked') return Boolean(a.unlocked_at);
    if (filter === 'locked') return !a.unlocked_at;
    return true;
  });

  return (
    <div className="w-full max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
      {/* Header */}
      <motion.div
        initial={{ opacity: 0, y: 10 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.35 }}
        className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
      >
        <div>
          <h1 className="text-xl sm:text-2xl font-rpg font-semibold text-stone-50 tracking-wide">
            Pencapaian
          </h1>
          <p className="text-xs text-stone-500 mt-1">
            Koleksi gelar dari perjalanan dan penyelesaian targetmu.
          </p>
        </div>

        {/* Progress */}
        <div className="flex items-center gap-3 px-4 py-2 rounded-xl bg-stone-900/60 border border-stone-800/70 text-xs">
          <Trophy className="w-4 h-4 text-amber-400" />
          <span className="text-stone-400">Terbuka:</span>
          <span className="font-mono font-bold text-amber-400">{unlockedCount}</span>
          <span className="text-stone-600">/</span>
          <span className="font-mono font-bold text-stone-300">{achievements.length}</span>
        </div>
      </motion.div>

      {/* Progress bar */}
      <motion.div
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ delay: 0.15 }}
        className="space-y-1.5"
      >
        <div className="w-full h-1.5 bg-stone-900 rounded-full overflow-hidden">
          <motion.div
            className="h-full rounded-full bg-amber-500"
            initial={{ width: 0 }}
            animate={{ width: achievements.length > 0 ? `${(unlockedCount / achievements.length) * 100}%` : '0%' }}
            transition={{ duration: 1, delay: 0.3, ease: [0.25, 0.1, 0.25, 1] }}
          />
        </div>
        <p className="text-[10px] text-stone-600 text-right font-mono">
          {achievements.length > 0 ? Math.round((unlockedCount / achievements.length) * 100) : 0}% selesai
        </p>
      </motion.div>

      {/* Filter Tabs */}
      <motion.div
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ delay: 0.1 }}
        className="flex items-center gap-1 bg-stone-900/50 p-1 rounded-xl border border-stone-800/60 w-fit"
      >
        {(['all', 'unlocked', 'locked'] as const).map((f) => (
          <button
            key={f}
            onClick={() => setFilter(f)}
            className={`relative px-4 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 ${
              filter === f ? 'text-amber-300' : 'text-stone-500 hover:text-stone-300'
            }`}
          >
            {filter === f && (
              <motion.div
                layoutId="ach-filter"
                className="absolute inset-0 bg-amber-500/10 border border-amber-500/20 rounded-lg"
                transition={{ type: 'spring', stiffness: 400, damping: 30 }}
              />
            )}
            <span className="relative z-10">
              {f === 'all' ? 'Semua' : f === 'unlocked' ? 'Terbuka' : 'Terkunci'}
            </span>
          </button>
        ))}
      </motion.div>

      {/* Achievement Grid */}
      {loading ? (
        <div className="py-20 flex flex-col items-center justify-center gap-2 text-stone-500">
          <Loader2 className="w-5 h-5 animate-spin" />
          <span className="text-[11px]">Memuat pencapaian...</span>
        </div>
      ) : (
        <motion.div
          layout
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
        >
          <AnimatePresence mode="popLayout">
            {filtered.map((ach, i) => {
              const isUnlocked = Boolean(ach.unlocked_at);
              const rarity = rarityConfig[ach.rarity] || rarityConfig.common;
              const emoji = achievementIcons[i % achievementIcons.length];

              return (
                <motion.div
                  key={ach.id}
                  layout
                  initial={{ opacity: 0, scale: 0.95 }}
                  animate={{ opacity: 1, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.9 }}
                  transition={{ delay: i * 0.04, duration: 0.25 }}
                  whileHover={isUnlocked ? { y: -3, transition: { duration: 0.15 } } : {}}
                  className={`p-4 rounded-2xl border flex flex-col justify-between transition-opacity duration-200 ${
                    isUnlocked
                      ? `bg-stone-900/60 ${rarity.border} ${rarity.glow}`
                      : 'bg-stone-950/60 border-stone-800/40 opacity-45 grayscale'
                  }`}
                >
                  <div>
                    {/* Badges row */}
                    <div className="flex items-center justify-between gap-2 mb-3">
                      <span className={`px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider border ${rarity.color} ${rarity.bg} ${rarity.border}`}>
                        {rarity.label}
                      </span>
                      {isUnlocked ? (
                        <span className="flex items-center gap-1 text-[10px] text-emerald-400 font-medium">
                          <CheckCircle2 className="w-3 h-3" /> Terbuka
                        </span>
                      ) : (
                        <span className="flex items-center gap-1 text-[10px] text-stone-600 font-medium">
                          <Lock className="w-3 h-3" /> Terkunci
                        </span>
                      )}
                    </div>

                    {/* Icon + Content */}
                    <div className="flex items-start gap-3 mb-4">
                      <div className={`w-11 h-11 rounded-xl border flex items-center justify-center flex-shrink-0 text-xl ${
                        isUnlocked ? `${rarity.bg} ${rarity.border}` : 'bg-stone-900 border-stone-800'
                      }`}>
                        {isUnlocked ? emoji : <Lock className="w-4 h-4 text-stone-700" />}
                      </div>
                      <div className="flex-1 min-w-0">
                        <h3 className={`text-sm font-semibold leading-tight ${isUnlocked ? 'text-stone-100' : 'text-stone-600'}`}>
                          {ach.title}
                        </h3>
                        <p className="text-[11px] text-stone-500 mt-1 line-clamp-2 leading-relaxed">
                          {ach.description}
                        </p>
                      </div>
                    </div>
                  </div>

                  {/* Reward footer */}
                  <div className="pt-2.5 border-t border-stone-800/60 flex items-center gap-2">
                    <div className="flex items-center gap-1 text-[11px] font-mono text-sky-400">
                      <Zap className="w-3 h-3" />
                      <span>+{ach.exp_reward}</span>
                    </div>
                    {ach.gold_reward > 0 && (
                      <div className="flex items-center gap-1 text-[11px] font-mono text-amber-400">
                        <Coins className="w-3 h-3" />
                        <span>+{ach.gold_reward}</span>
                      </div>
                    )}
                    {isUnlocked && ach.unlocked_at && (
                      <span className="ml-auto text-[10px] text-stone-600 font-mono">
                        {new Date(ach.unlocked_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })}
                      </span>
                    )}
                  </div>
                </motion.div>
              );
            })}
          </AnimatePresence>
        </motion.div>
      )}

      {!loading && filtered.length === 0 && (
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          className="py-16 text-center space-y-2"
        >
          <Star className="w-8 h-8 text-stone-700 mx-auto" />
          <p className="text-sm text-stone-500">Tidak ada pencapaian dalam kategori ini.</p>
        </motion.div>
      )}
    </div>
  );
};
