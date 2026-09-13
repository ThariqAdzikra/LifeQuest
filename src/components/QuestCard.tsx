import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Quest, QuestLog, DifficultyType } from '@/types';
import {
  Clock,
  CheckCircle,
  Upload,
  X,
  RotateCcw,
  Zap,
  Coins,
  Flame,
  Shield,
  Swords,
} from 'lucide-react';
import confetti from 'canvas-confetti';

interface QuestCardProps {
  quest?: Quest;
  log?: QuestLog;
  onTake?: (questId: number) => void;
  onComplete?: (logId: number) => void;
  onSubmitProof?: (log: QuestLog) => void;
  onCancel?: (logId: number) => void;
  onToggleStatus?: (questId: number) => void;
  onDelete?: (questId: number) => void;
}

const difficultyConfig: Record<DifficultyType, { label: string; color: string; bg: string; border: string; icon: React.ElementType }> = {
  easy: {
    label: 'Mudah',
    color: 'text-emerald-400',
    bg: 'bg-emerald-500/8',
    border: 'border-emerald-500/20',
    icon: Shield,
  },
  medium: {
    label: 'Sedang',
    color: 'text-amber-400',
    bg: 'bg-amber-500/8',
    border: 'border-amber-500/20',
    icon: Swords,
  },
  hard: {
    label: 'Sulit',
    color: 'text-rose-400',
    bg: 'bg-rose-500/8',
    border: 'border-rose-500/20',
    icon: Flame,
  },
};

const freqLabel: Record<string, string> = {
  daily: 'Harian',
  weekly: 'Mingguan',
  once: 'Sekali',
};

const statusConfig: Record<string, { label: string; color: string; bg: string; border: string }> = {
  active: { label: 'Aktif', color: 'text-sky-400', bg: 'bg-sky-500/8', border: 'border-sky-500/20' },
  pending_review: { label: 'Direview', color: 'text-amber-400', bg: 'bg-amber-500/8', border: 'border-amber-500/20' },
  rejected: { label: 'Revisi', color: 'text-rose-400', bg: 'bg-rose-500/8', border: 'border-rose-500/20' },
  completed: { label: 'Selesai', color: 'text-emerald-400', bg: 'bg-emerald-500/8', border: 'border-emerald-500/20' },
};

export const QuestCard: React.FC<QuestCardProps> = ({
  quest: initialQuest,
  log,
  onTake,
  onComplete,
  onSubmitProof,
  onCancel,
  onToggleStatus,
  onDelete,
}) => {
  const quest = log?.quest || initialQuest;
  const [completing, setCompleting] = useState(false);

  if (!quest) return null;

  const diff = difficultyConfig[quest.difficulty] || difficultyConfig.easy;
  const DiffIcon = diff.icon;
  const status = log ? statusConfig[log.status] : null;

  const handleComplete = async () => {
    if (!log || !onComplete) return;
    setCompleting(true);
    try {
      confetti({
        particleCount: 60,
        spread: 70,
        origin: { y: 0.65 },
        colors: ['#fbbf24', '#f59e0b', '#d97706', '#10b981'],
        ticks: 100,
      });
      await onComplete(log.id);
    } finally {
      setCompleting(false);
    }
  };

  return (
    <motion.div
      layout
      initial={{ opacity: 0, scale: 0.97 }}
      animate={{ opacity: 1, scale: 1 }}
      exit={{ opacity: 0, scale: 0.95 }}
      whileHover={{ y: -2, transition: { duration: 0.15 } }}
      transition={{ duration: 0.25 }}
      className="group p-5 rounded-2xl bg-stone-900/50 border border-stone-800/60 hover:border-amber-500/30 backdrop-blur-sm flex flex-col justify-between transition-all duration-200 hover:shadow-gold-sm"
    >
      <div>
        {/* Badges row */}
        <div className="flex items-center justify-between gap-2 mb-3">
          <div className="flex items-center gap-1.5">
            {/* Difficulty badge */}
            <div className={`flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium border ${diff.color} ${diff.bg} ${diff.border}`}>
              <DiffIcon className="w-3 h-3" />
              <span>{diff.label}</span>
            </div>
            {/* Frequency */}
            <span className="px-2 py-0.5 rounded-md text-[11px] text-stone-400 bg-stone-900/80 border border-stone-800/70 font-mono">
              {freqLabel[quest.frequency] || quest.frequency}
            </span>
          </div>

          {/* Status badge */}
          {status && (
            <span className={`px-2 py-0.5 rounded-md text-[11px] font-medium border ${status.color} ${status.bg} ${status.border}`}>
              {status.label}
            </span>
          )}
        </div>

        {/* Title */}
        <h3 className="text-sm font-semibold text-stone-100 group-hover:text-amber-200 transition-colors duration-200 line-clamp-1 mb-1">
          {quest.title}
        </h3>

        {/* Description */}
        <p className="text-xs text-stone-500 line-clamp-2 leading-relaxed mb-4">
          {quest.description || 'Tidak ada deskripsi untuk quest ini.'}
        </p>

        {/* Rejection note */}
        <AnimatePresence>
          {log?.status === 'rejected' && log.admin_notes && (
            <motion.div
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: 'auto' }}
              exit={{ opacity: 0, height: 0 }}
              className="mb-3 p-2.5 rounded-xl bg-rose-500/8 border border-rose-500/20 text-xs text-rose-300"
            >
              <span className="font-semibold">Catatan Admin: </span>
              {log.admin_notes}
            </motion.div>
          )}
        </AnimatePresence>

        {/* Reward row */}
        <div className="flex items-center gap-1.5 pt-3 border-t border-stone-800/50 mb-4">
          <div className="flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-500/8 border border-sky-500/20 text-[11px] font-mono text-sky-400">
            <Zap className="w-3 h-3" />
            <span>+{quest.exp_reward}</span>
          </div>
          <div className="flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/8 border border-amber-500/20 text-[11px] font-mono text-amber-400">
            <Coins className="w-3 h-3" />
            <span>+{quest.gold_reward}</span>
          </div>
          {quest.stat_reward_type && quest.stat_reward_value > 0 && (
            <span className="px-2 py-0.5 rounded-md bg-stone-800/60 text-stone-400 border border-stone-700/60 text-[11px] font-mono capitalize">
              +{quest.stat_reward_value} {quest.stat_reward_type.slice(0, 3).toUpperCase()}
            </span>
          )}
        </div>
      </div>

      {/* Action Footer */}
      <div className="flex items-center gap-2">
        {/* Active log */}
        {log?.status === 'active' && (
          <>
            {quest.is_admin_quest ? (
              <motion.button
                whileTap={{ scale: 0.97 }}
                onClick={() => onSubmitProof && onSubmitProof(log)}
                className="flex-1 py-2 px-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 text-xs font-semibold transition-colors duration-200 flex items-center justify-center gap-1.5 shadow-gold-sm"
              >
                <Upload className="w-3.5 h-3.5" />
                <span>Kirim Bukti</span>
              </motion.button>
            ) : (
              <motion.button
                whileTap={{ scale: 0.97 }}
                onClick={handleComplete}
                disabled={completing}
                className="flex-1 py-2 px-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-stone-950 text-xs font-semibold transition-colors duration-200 flex items-center justify-center gap-1.5 disabled:opacity-50"
              >
                <CheckCircle className="w-3.5 h-3.5" />
                <span>{completing ? 'Memproses...' : 'Selesaikan'}</span>
              </motion.button>
            )}
            {onCancel && (
              <motion.button
                whileTap={{ scale: 0.95 }}
                onClick={() => onCancel(log.id)}
                className="p-2 text-stone-600 hover:text-rose-400 hover:bg-rose-500/8 rounded-xl transition-all duration-200"
              >
                <X className="w-4 h-4" />
              </motion.button>
            )}
          </>
        )}

        {/* Rejected: re-upload */}
        {log?.status === 'rejected' && (
          <>
            <motion.button
              whileTap={{ scale: 0.97 }}
              onClick={() => onSubmitProof && onSubmitProof(log)}
              className="flex-1 py-2 px-3 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/25 text-amber-400 text-xs font-semibold transition-all duration-200 flex items-center justify-center gap-1.5"
            >
              <RotateCcw className="w-3.5 h-3.5" />
              <span>Unggah Ulang</span>
            </motion.button>
            {onCancel && (
              <motion.button
                whileTap={{ scale: 0.95 }}
                onClick={() => onCancel(log.id)}
                className="p-2 text-stone-600 hover:text-rose-400 hover:bg-rose-500/8 rounded-xl transition-all duration-200"
              >
                <X className="w-4 h-4" />
              </motion.button>
            )}
          </>
        )}

        {/* Take quest */}
        {!log && onTake && (
          <motion.button
            whileTap={{ scale: 0.97 }}
            onClick={() => onTake(quest.id)}
            className="w-full py-2 px-3 rounded-xl bg-stone-800 hover:bg-stone-700 border border-stone-700/60 text-stone-200 text-xs font-semibold transition-all duration-200 hover:border-amber-500/30 hover:text-amber-200"
          >
            Ambil Quest
          </motion.button>
        )}

        {/* Admin manage */}
        {onToggleStatus && onDelete && (
          <div className="flex items-center justify-between w-full gap-2">
            <motion.button
              whileTap={{ scale: 0.97 }}
              onClick={() => onToggleStatus(quest.id)}
              className="flex-1 py-1.5 px-3 rounded-lg text-xs font-medium bg-stone-800/80 hover:bg-stone-700/80 text-stone-300 border border-stone-700/60 transition-all duration-200"
            >
              {quest.is_active ? 'Jeda' : 'Aktifkan'}
            </motion.button>
            <motion.button
              whileTap={{ scale: 0.97 }}
              onClick={() => onDelete(quest.id)}
              className="py-1.5 px-3 rounded-lg text-xs font-medium text-rose-400 hover:bg-rose-500/8 border border-rose-500/20 transition-all duration-200"
            >
              Hapus
            </motion.button>
          </div>
        )}
      </div>
    </motion.div>
  );
};
