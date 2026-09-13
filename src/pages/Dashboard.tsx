import React, { useEffect, useState } from 'react';
import { motion } from 'framer-motion';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/lib/api';
import { StatsWidget } from '@/components/StatsWidget';
import { calculateLevelProgress } from '@/types';
import {
  CheckCircle2,
  ListTodo,
  Trophy,
  Zap,
  Clock,
  History,
  ArrowRight,
  Coins,
  Star,
} from 'lucide-react';
import { Link } from 'react-router-dom';

const containerVariants = {
  hidden: {},
  show: {
    transition: {
      staggerChildren: 0.08,
    },
  },
};

const cardVariants = {
  hidden: { opacity: 0, y: 16 },
  show: { opacity: 1, y: 0, transition: { duration: 0.35, ease: [0.25, 0.1, 0.25, 1] as [number, number, number, number] } },
};

export const Dashboard: React.FC = () => {
  const { profile } = useAuth();
  const [dashboardData, setDashboardData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [time, setTime] = useState(new Date());

  useEffect(() => {
    const timer = setInterval(() => setTime(new Date()), 1000);
    return () => clearInterval(timer);
  }, []);

  useEffect(() => {
    if (profile?.id) {
      api.getDashboard(profile.id).then((data) => {
        setDashboardData(data);
        setLoading(false);
      });
    }
  }, [profile?.id]);

  if (!profile) return null;

  const expInfo = calculateLevelProgress(profile.exp || 0);

  const formattedTime = time.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  });

  const formattedDate = time.toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });

  const metricCards = [
    {
      label: 'Quest Diambil',
      value: dashboardData?.totalQuests ?? 0,
      sub: 'Total pernah diambil',
      icon: ListTodo,
      color: 'text-stone-300',
      iconBg: 'bg-stone-700/40 border-stone-700/60',
    },
    {
      label: 'Diselesaikan',
      value: dashboardData?.completedQuests ?? 0,
      sub: 'Quest terwujud',
      icon: CheckCircle2,
      color: 'text-emerald-400',
      iconBg: 'bg-emerald-500/10 border-emerald-500/20',
    },
    {
      label: 'Achievements',
      value: `${dashboardData?.achievements ?? 0}/${dashboardData?.totalAchievements ?? 7}`,
      sub: 'Gelar diraih',
      icon: Trophy,
      color: 'text-amber-400',
      iconBg: 'bg-amber-500/10 border-amber-500/20',
    },
    {
      label: 'Total EXP',
      value: (profile.exp || 0).toLocaleString(),
      sub: 'Poin pengalaman',
      icon: Zap,
      color: 'text-sky-400',
      iconBg: 'bg-sky-500/10 border-sky-500/20',
    },
  ];

  return (
    <div className="w-full max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8 space-y-5">

      {/* Profile & EXP Bar */}
      <motion.div
        initial={{ opacity: 0, y: 12 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
        className="p-5 sm:p-6 rounded-2xl bg-stone-900/60 border border-stone-800/70 backdrop-blur-sm"
      >
        <div className="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-6 justify-between">
          {/* Avatar / Identity */}
          <div className="flex items-center gap-4">
            <div className="relative flex-shrink-0">
              <div className="w-14 h-14 rounded-2xl overflow-hidden border border-amber-500/20 bg-stone-800 shadow-gold-sm">
                <img
                  src={profile.avatar_url || '/images/char.png'}
                  alt={profile.name}
                  className="w-full h-full object-cover"
                  onError={(e) => { e.currentTarget.src = '/images/char.png'; }}
                />
              </div>
              <div className="absolute -bottom-1.5 -right-1.5 px-1.5 py-0.5 rounded-md bg-amber-500 text-stone-950 text-[10px] font-bold shadow-gold-sm">
                Lv.{expInfo.currentLevel}
              </div>
            </div>
            <div>
              <h1 className="text-lg font-semibold text-stone-50 tracking-tight">
                {profile.name}
              </h1>
              <div className="flex items-center gap-2 mt-0.5">
                <span className="text-[11px] text-stone-400">
                  {profile.is_admin ? 'Administrator' : 'Petualang'}
                </span>
                <span className="text-stone-700">·</span>
                <span className="flex items-center gap-1 text-[11px] text-amber-400/80">
                  <Coins className="w-3 h-3" />
                  <span className="font-mono font-semibold">{profile.gold || 0}</span>
                  <span className="text-stone-500">gold</span>
                </span>
              </div>
            </div>
          </div>

          {/* EXP Progress */}
          <div className="w-full sm:w-72 space-y-2">
            <div className="flex justify-between items-center text-xs">
              <span className="text-stone-400 font-medium">Progres Level {expInfo.currentLevel}</span>
              <div className="flex items-center gap-1.5 text-stone-300 font-mono">
                <Star className="w-3 h-3 text-amber-400" />
                <span>{expInfo.progressPercent}%</span>
              </div>
            </div>
            <div className="w-full bg-stone-950 rounded-full h-2 overflow-hidden border border-stone-800/60">
              <motion.div
                className="h-full rounded-full bg-amber-500 relative"
                initial={{ width: 0 }}
                animate={{ width: `${expInfo.progressPercent}%` }}
                transition={{ duration: 1, delay: 0.3, ease: [0.25, 0.1, 0.25, 1] }}
              >
                <div className="absolute inset-0 bg-gradient-to-r from-amber-600 to-amber-400 rounded-full" />
              </motion.div>
            </div>
            <div className="flex justify-between text-[10px] text-stone-500 font-mono">
              <span>{expInfo.currentExp.toLocaleString()} XP</span>
              <span>Target: {expInfo.nextLevelMinExp.toLocaleString()} XP</span>
            </div>
          </div>
        </div>
      </motion.div>

      {/* Metric Cards */}
      <motion.div
        variants={containerVariants}
        initial="hidden"
        animate="show"
        className="grid grid-cols-2 sm:grid-cols-4 gap-3.5"
      >
        {metricCards.map((card) => {
          const Icon = card.icon;
          return (
            <motion.div
              key={card.label}
              variants={cardVariants}
              whileHover={{ y: -2, transition: { duration: 0.15 } }}
              className="p-4 rounded-xl bg-stone-900/50 border border-stone-800/60 space-y-2.5"
            >
              <div className="flex items-center justify-between">
                <span className="text-[11px] text-stone-400">{card.label}</span>
                <div className={`w-6 h-6 rounded-lg border flex items-center justify-center ${card.iconBg}`}>
                  <Icon className={`w-3.5 h-3.5 ${card.color}`} />
                </div>
              </div>
              <div className={`text-xl font-bold font-mono ${card.color}`}>
                {card.value}
              </div>
              <p className="text-[10px] text-stone-500">{card.sub}</p>
            </motion.div>
          );
        })}
      </motion.div>

      {/* Middle Row */}
      <div className="grid grid-cols-1 md:grid-cols-12 gap-4">
        {/* Left: Clock + Activity */}
        <div className="md:col-span-7 space-y-4">
          {/* Clock widget */}
          <motion.div
            initial={{ opacity: 0, x: -12 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.4, delay: 0.2 }}
            className="p-5 rounded-2xl bg-stone-900/40 border border-stone-800/60 grid grid-cols-2 gap-5"
          >
            <div>
              <div className="flex items-center gap-1.5 text-[11px] text-stone-500 mb-2 uppercase tracking-wider">
                <Clock className="w-3 h-3" />
                <span>Waktu</span>
              </div>
              <div className="text-2xl font-mono font-bold text-stone-100 tracking-tight">
                {formattedTime}
              </div>
              <div className="text-[11px] text-stone-500 mt-1 capitalize">{formattedDate}</div>
            </div>
            <div className="border-l border-stone-800/80 pl-5">
              <div className="text-[11px] text-stone-500 mb-2 uppercase tracking-wider">Status</div>
              <div className="flex items-center gap-2 mt-1">
                <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
                <span className="text-xs text-emerald-400 font-medium">Aktif</span>
              </div>
              <div className="text-[11px] text-stone-500 mt-1">
                Level {expInfo.currentLevel} · {expInfo.currentExp} XP
              </div>
            </div>
          </motion.div>

          {/* Recent Activities */}
          <motion.div
            initial={{ opacity: 0, x: -12 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.4, delay: 0.3 }}
            className="p-5 rounded-2xl bg-stone-900/40 border border-stone-800/60 space-y-4"
          >
            <div className="flex items-center justify-between">
              <h3 className="text-xs font-semibold text-stone-300 uppercase tracking-wider flex items-center gap-1.5">
                <History className="w-3.5 h-3.5 text-stone-400" />
                Aktivitas Terbaru
              </h3>
              <Link
                to="/quests"
                className="text-[11px] text-amber-500 hover:text-amber-400 flex items-center gap-1 transition-colors"
              >
                <span>Papan Quest</span>
                <ArrowRight className="w-3 h-3" />
              </Link>
            </div>

            {!loading && dashboardData?.recentActivities?.length > 0 ? (
              <div className="space-y-0 divide-y divide-stone-800/50">
                {dashboardData.recentActivities.map((act: any, i: number) => (
                  <motion.div
                    key={act.id}
                    initial={{ opacity: 0, x: -8 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 0.35 + i * 0.06 }}
                    className="py-3 flex items-center justify-between gap-3 text-xs"
                  >
                    <div>
                      <div className="font-medium text-stone-200 truncate max-w-[180px]">
                        {act.quest?.title || 'Quest Selesai'}
                      </div>
                      <div className="text-[10px] text-stone-500 mt-0.5">
                        {new Date(act.updated_at).toLocaleDateString('id-ID', {
                          day: 'numeric',
                          month: 'short',
                          hour: '2-digit',
                          minute: '2-digit',
                        })}
                      </div>
                    </div>
                    <div className="flex items-center gap-2 font-mono text-[11px] flex-shrink-0">
                      <span className="px-2 py-0.5 rounded-md bg-sky-500/10 border border-sky-500/20 text-sky-400">
                        +{act.quest?.exp_reward || 30} XP
                      </span>
                      <span className="px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-400">
                        +{act.quest?.gold_reward || 15} G
                      </span>
                    </div>
                  </motion.div>
                ))}
              </div>
            ) : (
              <div className="py-8 text-center text-xs text-stone-500">
                Belum ada aktivitas yang tercatat.
              </div>
            )}
          </motion.div>
        </div>

        {/* Right: RPG Attributes */}
        <motion.div
          initial={{ opacity: 0, x: 12 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.4, delay: 0.25 }}
          className="md:col-span-5"
        >
          <StatsWidget profile={profile} />
        </motion.div>
      </div>
    </div>
  );
};
