import React, { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/lib/api';
import { 
  Loader2, 
  Crown, 
  Medal, 
  Award, 
  Sword, 
  Zap, 
  Star, 
  Sparkles,
  ArrowRight
} from 'lucide-react';
import { Link } from 'react-router-dom';

import type { Variants } from 'framer-motion';

const podiumContainerVariants: Variants = {
  hidden: {},
  show: {
    transition: {
      staggerChildren: 0.12,
    },
  },
};

const podiumCardVariants: Variants = {
  hidden: { opacity: 0, y: 35 },
  show: {
    opacity: 1,
    y: 0,
    transition: {
      duration: 0.45,
      ease: 'easeOut',
    },
  },
};

const championVariants: Variants = {
  hidden: { opacity: 0, y: 45, scale: 0.95 },
  show: {
    opacity: 1,
    y: 0,
    scale: 1,
    transition: {
      type: 'spring',
      damping: 15,
      stiffness: 120,
      delay: 0.1,
    },
  },
};

export const Leaderboard: React.FC = () => {
  const { profile } = useAuth();
  const [leaderboardData, setLeaderboardData] = useState<{
    topUsers: any[];
    currentUserRank: number | null;
    currentUserQuestCount: number;
  }>({
    topUsers: [],
    currentUserRank: null,
    currentUserQuestCount: 0,
  });
  const [loading, setLoading] = useState(true);
  const [sortBy, setSortBy] = useState<'quests' | 'exp'>('quests');

  useEffect(() => {
    api.getLeaderboard(profile?.id).then((data) => {
      setLeaderboardData(data);
      setLoading(false);
    });
  }, [profile?.id]);

  const sortedUsers = [...leaderboardData.topUsers].sort((a, b) => {
    if (sortBy === 'quests') {
      const qDiff = (b.quest_logs_count || 0) - (a.quest_logs_count || 0);
      if (qDiff !== 0) return qDiff;
      return (b.exp || 0) - (a.exp || 0);
    } else {
      const expDiff = (b.exp || 0) - (a.exp || 0);
      if (expDiff !== 0) return expDiff;
      return (b.quest_logs_count || 0) - (a.quest_logs_count || 0);
    }
  });

  const getRankIcon = (idx: number) => {
    if (idx === 0) return <Crown className="w-4 h-4 text-amber-400" />;
    if (idx === 1) return <Medal className="w-4 h-4 text-slate-300" />;
    if (idx === 2) return <Award className="w-4 h-4 text-amber-600" />;
    return (
      <span className="w-5 h-5 flex items-center justify-center font-mono text-xs text-stone-500">
        {idx + 1}
      </span>
    );
  };

  return (
    <div className="w-full max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8 space-y-6">

      {/* ─── Header ──────────────────────────────────────────────────────── */}
      <motion.div
        initial={{ opacity: 0, y: 10 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.35 }}
        className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
      >
        <div>
          <h1 className="text-xl sm:text-2xl font-rpg font-semibold text-stone-50 tracking-wide">
            Papan Peringkat
          </h1>
          <p className="text-xs text-stone-500 mt-1">
            20 petualang teratas — diurutkan berdasarkan quest selesai, lalu EXP total.
          </p>
        </div>

        {/* Sort Switcher */}
        <div className="inline-flex items-center p-1 rounded-xl bg-stone-900/70 border border-stone-800/70 backdrop-blur-sm">
          <button
            onClick={() => setSortBy('quests')}
            className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-all ${
              sortBy === 'quests'
                ? 'bg-amber-500 text-stone-950 font-semibold shadow-gold-sm'
                : 'text-stone-400 hover:text-stone-200'
            }`}
          >
            <Sword className="w-3.5 h-3.5" />
            <span>Quest Selesai</span>
          </button>
          <button
            onClick={() => setSortBy('exp')}
            className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-all ${
              sortBy === 'exp'
                ? 'bg-amber-500 text-stone-950 font-semibold shadow-gold-sm'
                : 'text-stone-400 hover:text-stone-200'
            }`}
          >
            <Zap className="w-3.5 h-3.5" />
            <span>Total EXP</span>
          </button>
        </div>
      </motion.div>

      {/* ─── Top 3 Podium (High-impact Visual Focus) ──────────────────────── */}
      {!loading && sortedUsers.length >= 3 && (
        <motion.div
          variants={podiumContainerVariants}
          initial="hidden"
          animate="show"
          className="grid grid-cols-3 gap-3 sm:gap-5 items-end pt-6 pb-2"
        >

          {/* ═══ RANK 2: SILVER (LEFT) ═══ */}
          {sortedUsers[1] && (
            <motion.div
              variants={podiumCardVariants}
              whileHover={{ y: -3, transition: { duration: 0.15 } }}
              className="rounded-2xl bg-stone-900/50 border border-stone-800/70 backdrop-blur-sm p-4 sm:p-5 text-center flex flex-col items-center justify-between space-y-3 hover:border-stone-700/80 transition-colors"
            >
              <div className="w-full space-y-2.5">
                {/* Silver Medal Badge */}
                <div className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-stone-800 border border-stone-700/60 text-stone-300 text-[10px] font-mono font-medium">
                  <Medal className="w-3.5 h-3.5 text-stone-400" />
                  <span>#2</span>
                </div>

                {/* Avatar */}
                <div className="relative mx-auto w-14 h-14 sm:w-16 sm:h-16">
                  <div className="w-full h-full rounded-2xl overflow-hidden border border-stone-700/80 bg-stone-800">
                    <img
                      src={sortedUsers[1].avatar_url || '/images/char.png'}
                      alt={sortedUsers[1].name}
                      className="w-full h-full object-cover"
                      onError={(e) => { e.currentTarget.src = '/images/char.png'; }}
                    />
                  </div>
                  <div className="absolute -bottom-1 -right-1 px-1.5 py-0.2 rounded-md bg-stone-800 border border-stone-700 text-stone-300 text-[9px] font-mono font-bold">
                    Lv.{sortedUsers[1].level || 1}
                  </div>
                </div>

                {/* Name */}
                <div>
                  <h3 className="font-semibold text-xs sm:text-sm text-stone-200 truncate px-1">
                    {sortedUsers[1].name}
                  </h3>
                  <span className="text-[10px] text-stone-500 font-mono">Peringkat 2</span>
                </div>
              </div>

              {/* Stats */}
              <div className="w-full pt-2.5 border-t border-stone-800/70 flex items-center justify-center gap-2 font-mono text-xs">
                <span className="px-2 py-0.5 rounded-md bg-stone-800/80 text-stone-300 border border-stone-700/60 font-semibold">
                  {sortedUsers[1].quest_logs_count || 0} Quest
                </span>
                <span className="text-stone-400 text-[11px]">
                  {(sortedUsers[1].exp || 0).toLocaleString()} XP
                </span>
              </div>
            </motion.div>
          )}

          {/* ═══ RANK 1: GOLD GRAND CHAMPION (CENTER - ELEVATED) ═══ */}
          {sortedUsers[0] && (
            <motion.div
              variants={championVariants}
              whileHover={{ y: -5, scale: 1.01, transition: { duration: 0.15 } }}
              className="rounded-2xl bg-stone-900/80 border border-amber-500/40 backdrop-blur-sm p-5 sm:p-6 text-center flex flex-col items-center justify-between space-y-3.5 -translate-y-3 sm:-translate-y-4 shadow-gold-sm"
            >
              <div className="w-full space-y-2.5">
                {/* Floating Gold Crown */}
                <motion.div
                  animate={{ y: [0, -3, 0] }}
                  transition={{ duration: 2.5, repeat: Infinity, ease: 'easeInOut' }}
                  className="flex justify-center"
                >
                  <Crown className="w-6 h-6 text-amber-400" />
                </motion.div>

                {/* Champion Pill */}
                <div className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-300 text-[10px] font-semibold tracking-wide">
                  <Sparkles className="w-3 h-3 text-amber-400" />
                  <span>Juara 1</span>
                </div>

                {/* Avatar with Amber Border */}
                <div className="relative mx-auto w-16 h-16 sm:w-20 sm:h-20">
                  <div className="w-full h-full rounded-2xl overflow-hidden border-2 border-amber-500/40 bg-stone-800">
                    <img
                      src={sortedUsers[0].avatar_url || '/images/char.png'}
                      alt={sortedUsers[0].name}
                      className="w-full h-full object-cover"
                      onError={(e) => { e.currentTarget.src = '/images/char.png'; }}
                    />
                  </div>
                  <div className="absolute -bottom-1 -right-1 px-2 py-0.5 rounded-md bg-amber-500 text-stone-950 text-[10px] font-bold font-mono">
                    Lv.{sortedUsers[0].level || 1}
                  </div>
                </div>

                {/* Name */}
                <div>
                  <h3 className="font-semibold text-sm sm:text-base text-stone-100 truncate px-1">
                    {sortedUsers[0].name}
                  </h3>
                  <span className="text-[10px] text-amber-400/80 font-mono font-medium">Pemimpin Klasemen</span>
                </div>
              </div>

              {/* Stats */}
              <div className="w-full pt-3 border-t border-stone-800/70 flex items-center justify-center gap-2 font-mono">
                <span className="px-2.5 py-0.5 rounded-md bg-amber-500/15 border border-amber-500/30 text-amber-300 font-bold text-xs flex items-center gap-1">
                  <Sword className="w-3.5 h-3.5" />
                  <span>{sortedUsers[0].quest_logs_count || 0} Quest</span>
                </span>
                <span className="px-2.5 py-0.5 rounded-md bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-medium">
                  {(sortedUsers[0].exp || 0).toLocaleString()} XP
                </span>
              </div>
            </motion.div>
          )}

          {/* ═══ RANK 3: BRONZE (RIGHT) ═══ */}
          {sortedUsers[2] && (
            <motion.div
              variants={podiumCardVariants}
              whileHover={{ y: -3, transition: { duration: 0.15 } }}
              className="rounded-2xl bg-stone-900/50 border border-stone-800/70 backdrop-blur-sm p-4 sm:p-5 text-center flex flex-col items-center justify-between space-y-3 hover:border-stone-700/80 transition-colors"
            >
              <div className="w-full space-y-2.5">
                {/* Bronze Badge */}
                <div className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-stone-800 border border-stone-700/60 text-amber-600 text-[10px] font-mono font-medium">
                  <Award className="w-3.5 h-3.5 text-amber-600" />
                  <span>#3</span>
                </div>

                {/* Avatar */}
                <div className="relative mx-auto w-14 h-14 sm:w-16 sm:h-16">
                  <div className="w-full h-full rounded-2xl overflow-hidden border border-stone-700/80 bg-stone-800">
                    <img
                      src={sortedUsers[2].avatar_url || '/images/char.png'}
                      alt={sortedUsers[2].name}
                      className="w-full h-full object-cover"
                      onError={(e) => { e.currentTarget.src = '/images/char.png'; }}
                    />
                  </div>
                  <div className="absolute -bottom-1 -right-1 px-1.5 py-0.2 rounded-md bg-stone-800 border border-stone-700 text-stone-300 text-[9px] font-mono font-bold">
                    Lv.{sortedUsers[2].level || 1}
                  </div>
                </div>

                {/* Name */}
                <div>
                  <h3 className="font-semibold text-xs sm:text-sm text-stone-200 truncate px-1">
                    {sortedUsers[2].name}
                  </h3>
                  <span className="text-[10px] text-stone-500 font-mono">Peringkat 3</span>
                </div>
              </div>

              {/* Stats */}
              <div className="w-full pt-2.5 border-t border-stone-800/70 flex items-center justify-center gap-2 font-mono text-xs">
                <span className="px-2 py-0.5 rounded-md bg-stone-800/80 text-stone-300 border border-stone-700/60 font-semibold">
                  {sortedUsers[2].quest_logs_count || 0} Quest
                </span>
                <span className="text-stone-400 text-[11px]">
                  {(sortedUsers[2].exp || 0).toLocaleString()} XP
                </span>
              </div>
            </motion.div>
          )}

        </motion.div>
      )}

      {/* ─── Your Rank Card ──────────────────────────────────────────────── */}
      {profile && !profile.is_admin && (
        <motion.div
          initial={{ opacity: 0, y: 8 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.35, delay: 0.2 }}
          className="p-4 rounded-xl bg-stone-900/60 border border-amber-900/30 flex items-center justify-between text-xs backdrop-blur-sm"
        >
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-full overflow-hidden border border-amber-500/20 bg-stone-800 flex-shrink-0">
              <img
                src={profile.avatar_url || '/images/char.png'}
                alt={profile.name}
                className="w-full h-full object-cover"
                onError={(e) => { e.currentTarget.src = '/images/char.png'; }}
              />
            </div>
            <div>
              <div className="font-medium text-stone-200 flex items-center gap-1.5">
                <span>{profile.name}</span>
                <span className="text-[9px] px-1.5 py-0.2 rounded bg-amber-500/15 border border-amber-500/25 text-amber-400 font-semibold uppercase">
                  Profilmu
                </span>
              </div>
              <div className="text-stone-500 text-[10px]">Posisimu saat ini di klasemen</div>
            </div>
          </div>
          <div className="flex items-center gap-5">
            <div className="text-right">
              <div className="text-stone-500 text-[10px] mb-0.5">Peringkat</div>
              <div className="font-mono font-bold text-amber-400 text-sm">
                {leaderboardData.currentUserRank ? `#${leaderboardData.currentUserRank}` : '—'}
              </div>
            </div>
            <div className="text-right">
              <div className="text-stone-500 text-[10px] mb-0.5">Quest Selesai</div>
              <div className="font-mono font-bold text-stone-200 text-sm">
                {leaderboardData.currentUserQuestCount}
              </div>
            </div>
            <Link
              to="/quests"
              className="px-3 py-1.5 rounded-lg bg-stone-800 hover:bg-stone-700 border border-stone-700/60 text-stone-200 hover:text-amber-300 text-xs font-medium flex items-center gap-1 transition-colors"
            >
              <span>Buka Quest</span>
              <ArrowRight className="w-3 h-3" />
            </Link>
          </div>
        </motion.div>
      )}

      {/* ─── Leaderboard Table ───────────────────────────────────────────── */}
      <motion.div
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ delay: 0.25 }}
        className="bg-stone-900/40 border border-stone-800/70 rounded-2xl overflow-hidden backdrop-blur-sm"
      >
        {loading ? (
          <div className="py-20 flex flex-col items-center justify-center gap-2 text-stone-500">
            <Loader2 className="w-5 h-5 animate-spin text-amber-500/60" />
            <span className="text-[11px] font-rpg uppercase tracking-wider">Memuat peringkat...</span>
          </div>
        ) : (
          <div className="overflow-x-auto scrollbar-thin">
            <table className="w-full text-left text-xs">
              <thead className="bg-stone-950/60 border-b border-stone-800/70 text-[10px] uppercase tracking-widest text-stone-500 font-mono">
                <tr>
                  <th className="py-3 px-4 text-center w-12">#</th>
                  <th className="py-3 px-4">Petualang</th>
                  <th className="py-3 px-4 text-center">
                    <div className="flex items-center justify-center gap-1">
                      <Star className="w-3 h-3 text-amber-400" />
                      <span>Level</span>
                    </div>
                  </th>
                  <th className="py-3 px-4 text-center">
                    <div className="flex items-center justify-center gap-1">
                      <Sword className="w-3 h-3 text-amber-400" />
                      <span>Quest</span>
                    </div>
                  </th>
                  <th className="py-3 px-4 text-right">
                    <div className="flex items-center justify-end gap-1">
                      <Zap className="w-3 h-3 text-sky-400" />
                      <span>EXP</span>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-stone-800/40">
                <AnimatePresence>
                  {sortedUsers.map((user, idx) => {
                    const isCurrent = user.id === profile?.id;
                    return (
                      <motion.tr
                        key={user.id}
                        initial={{ opacity: 0, x: -8 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ delay: idx * 0.03, duration: 0.2 }}
                        className={`transition-colors duration-150 ${
                          isCurrent
                            ? 'bg-amber-500/10 border-l-2 border-amber-500/60'
                            : idx === 0
                            ? 'bg-amber-500/5'
                            : idx === 1
                            ? 'bg-stone-700/10'
                            : idx === 2
                            ? 'bg-amber-700/5'
                            : 'hover:bg-stone-800/30'
                        }`}
                      >
                        <td className="py-3 px-4">
                          <div className="flex justify-center items-center">
                            {getRankIcon(idx)}
                          </div>
                        </td>
                        <td className="py-3 px-4">
                          <div className="flex items-center gap-2.5">
                            <div className="w-7 h-7 rounded-full overflow-hidden border border-stone-700/60 bg-stone-800 flex-shrink-0">
                              <img
                                src={user.avatar_url || '/images/char.png'}
                                alt={user.name}
                                className="w-full h-full object-cover"
                                onError={(e) => { e.currentTarget.src = '/images/char.png'; }}
                              />
                            </div>
                            <div className="flex items-center gap-2">
                              <span className={`font-medium ${isCurrent ? 'text-amber-200' : 'text-stone-200'}`}>
                                {user.name}
                              </span>
                              {isCurrent && (
                                <span className="text-[9px] px-1.5 py-0.5 rounded bg-amber-500/15 border border-amber-500/25 text-amber-400 font-medium font-mono">
                                  Kamu
                                </span>
                              )}
                            </div>
                          </div>
                        </td>
                        <td className="py-3 px-4 text-center font-mono text-stone-400">
                          {user.level || 1}
                        </td>
                        <td className="py-3 px-4 text-center font-mono font-semibold text-amber-400">
                          {user.quest_logs_count || 0}
                        </td>
                        <td className="py-3 px-4 text-right font-mono text-stone-400">
                          {(user.exp || 0).toLocaleString()}
                        </td>
                      </motion.tr>
                    );
                  })}
                </AnimatePresence>
              </tbody>
            </table>
          </div>
        )}
      </motion.div>
    </div>
  );
};
