import React from 'react';
import { motion } from 'framer-motion';
import { Profile } from '@/types';
import { Brain, Dumbbell, Heart, Zap } from 'lucide-react';

interface StatsWidgetProps {
  profile: Profile;
}

const stats = [
  {
    key: 'intelligence' as const,
    label: 'Kecerdasan',
    name: 'INT',
    icon: Brain,
    color: 'text-sky-400',
    bg: 'bg-sky-500/8',
    border: 'border-sky-500/20',
    bar: 'bg-sky-400',
  },
  {
    key: 'strength' as const,
    label: 'Kekuatan',
    name: 'STR',
    icon: Dumbbell,
    color: 'text-rose-400',
    bg: 'bg-rose-500/8',
    border: 'border-rose-500/20',
    bar: 'bg-rose-400',
  },
  {
    key: 'stamina' as const,
    label: 'Daya Tahan',
    name: 'STA',
    icon: Heart,
    color: 'text-emerald-400',
    bg: 'bg-emerald-500/8',
    border: 'border-emerald-500/20',
    bar: 'bg-emerald-400',
  },
  {
    key: 'agility' as const,
    label: 'Kelincahan',
    name: 'AGI',
    icon: Zap,
    color: 'text-amber-400',
    bg: 'bg-amber-500/8',
    border: 'border-amber-500/20',
    bar: 'bg-amber-400',
  },
];

export const StatsWidget: React.FC<StatsWidgetProps> = ({ profile }) => {
  const maxStat = Math.max(
    ...stats.map((s) => profile[s.key] || 0),
    1
  );

  return (
    <div className="h-full p-5 rounded-2xl bg-stone-900/40 border border-stone-800/60 space-y-4">
      {/* Header */}
      <div className="flex items-center justify-between">
        <h3 className="text-xs font-semibold text-stone-300 uppercase tracking-widest">
          Atribut
        </h3>
        <span className="text-[10px] text-stone-500 font-mono">BASE STATS</span>
      </div>

      {/* Horizontal rule */}
      <div className="w-full h-px bg-stone-800/80" />

      {/* Stat Rows */}
      <div className="space-y-4">
        {stats.map((stat, i) => {
          const Icon = stat.icon;
          const value = profile[stat.key] || 0;
          const pct = Math.min((value / Math.max(maxStat, 50)) * 100, 100);

          return (
            <motion.div
              key={stat.key}
              initial={{ opacity: 0, x: 12 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 0.1 + i * 0.07, duration: 0.35 }}
              className="space-y-1.5"
            >
              <div className="flex items-center justify-between text-xs">
                <div className="flex items-center gap-2">
                  <div className={`w-6 h-6 rounded-lg border flex items-center justify-center ${stat.bg} ${stat.border}`}>
                    <Icon className={`w-3.5 h-3.5 ${stat.color}`} />
                  </div>
                  <div>
                    <span className={`font-semibold font-mono text-[11px] ${stat.color}`}>{stat.name}</span>
                    <span className="text-stone-500 text-[10px] ml-1.5">{stat.label}</span>
                  </div>
                </div>
                <span className={`font-mono font-bold text-sm ${stat.color}`}>{value}</span>
              </div>

              {/* Stat bar */}
              <div className="w-full h-1.5 bg-stone-950 rounded-full overflow-hidden">
                <motion.div
                  className={`h-full rounded-full ${stat.bar}`}
                  initial={{ width: 0 }}
                  animate={{ width: `${Math.max(pct, 2)}%` }}
                  transition={{ duration: 0.7, delay: 0.2 + i * 0.07, ease: [0.25, 0.1, 0.25, 1] }}
                />
              </div>
            </motion.div>
          );
        })}
      </div>

      {/* Footer */}
      <div className="pt-2 border-t border-stone-800/80">
        <p className="text-[10px] text-stone-600 text-center leading-relaxed">
          Atribut meningkat saat menyelesaikan quest yang relevan
        </p>
      </div>
    </div>
  );
};
