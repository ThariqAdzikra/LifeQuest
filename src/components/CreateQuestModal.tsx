import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { DifficultyType, FrequencyType, StatType, calculateDefaultRewards } from '@/types';
import { supabase, isSupabaseConfigured } from '@/lib/supabase';
import { useAuth } from '@/context/AuthContext';
import { X, PlusCircle, Sparkles, Coins, Brain, Dumbbell, Heart, Zap, Loader2 } from 'lucide-react';

interface CreateQuestModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSuccess: () => void;
}

export const CreateQuestModal: React.FC<CreateQuestModalProps> = ({
  isOpen,
  onClose,
  onSuccess,
}) => {
  const { profile } = useAuth();
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [difficulty, setDifficulty] = useState<DifficultyType>('easy');
  const [frequency, setFrequency] = useState<FrequencyType>('daily');
  const [statType, setStatType] = useState<StatType | ''>('intelligence');
  
  // Custom rewards
  const defaultRewards = calculateDefaultRewards(difficulty, statType as StatType);
  const [expReward, setExpReward] = useState<number>(defaultRewards.exp);
  const [goldReward, setGoldReward] = useState<number>(defaultRewards.gold);
  const [statValue, setStatValue] = useState<number>(defaultRewards.stat);
  const [loading, setLoading] = useState(false);

  if (!isOpen) return null;

  const handleDifficultyChange = (diff: DifficultyType) => {
    setDifficulty(diff);
    const def = calculateDefaultRewards(diff, statType as StatType);
    setExpReward(def.exp);
    setGoldReward(def.gold);
    setStatValue(def.stat);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!title.trim() || !profile) return;

    setLoading(true);

    try {
      if (isSupabaseConfigured) {
        await supabase.from('quests').insert({
          title,
          description,
          difficulty,
          frequency,
          exp_reward: expReward,
          gold_reward: goldReward,
          stat_reward_type: statType || null,
          stat_reward_value: statType ? statValue : 0,
          creator_id: profile.id,
          is_admin_quest: false,
          is_active: true,
        });
      }

      onSuccess();
      onClose();
    } catch (err) {
      console.error('Error creating quest:', err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fade-in">
      <motion.div
        initial={{ opacity: 0, scale: 0.95, y: 10 }}
        animate={{ opacity: 1, scale: 1, y: 0 }}
        exit={{ opacity: 0, scale: 0.95, y: 10 }}
        transition={{ duration: 0.2 }}
        className="bg-stone-950/95 border border-stone-800/80 rounded-2xl max-w-lg w-full p-6 shadow-dark-lg relative backdrop-blur-xl text-stone-100"
      >
        <button
          onClick={onClose}
          className="absolute top-4 right-4 p-2 text-stone-500 hover:text-stone-200 rounded-lg hover:bg-stone-800/60 transition-colors"
        >
          <X className="w-5 h-5" />
        </button>

        <div className="mb-5">
          <div className="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold mb-2">
            <PlusCircle className="w-3.5 h-3.5" />
            <span>Buat Quest Kustom</span>
          </div>
          <h2 className="text-xl font-rpg font-semibold text-stone-100 tracking-wide">
            Rancang Quest Pribadimu
          </h2>
          <p className="text-xs text-stone-400 mt-1 leading-relaxed">
            Buat kebiasaan produktif harianmu sendiri dan raih EXP serta peningkatan stat karakter!
          </p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Title */}
          <div>
            <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
              Judul Quest *
            </label>
            <input
              type="text"
              required
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              placeholder="Contoh: Baca Buku 20 Halaman, Belajar Bahasa Asing..."
              className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2.5 text-xs text-stone-100 placeholder:text-stone-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
            />
          </div>

          {/* Description */}
          <div>
            <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
              Deskripsi (Opsional)
            </label>
            <textarea
              rows={2}
              value={description}
              onChange={(e) => setDescription(e.target.value)}
              placeholder="Rincian target atau instruksi..."
              className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2 text-xs text-stone-100 placeholder:text-stone-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
            />
          </div>

          {/* Difficulty & Frequency */}
          <div className="grid grid-cols-2 gap-3">
            <div>
              <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                Tingkat Kesulitan
              </label>
              <select
                value={difficulty}
                onChange={(e) => handleDifficultyChange(e.target.value as DifficultyType)}
                className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2 text-xs text-stone-100 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
              >
                <option value="easy" className="bg-stone-950 text-stone-100">Mudah (+10 XP)</option>
                <option value="medium" className="bg-stone-950 text-stone-100">Sedang (+100 XP)</option>
                <option value="hard" className="bg-stone-950 text-stone-100">Sulit (+1000 XP)</option>
              </select>
            </div>

            <div>
              <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
                Frekuensi
              </label>
              <select
                value={frequency}
                onChange={(e) => setFrequency(e.target.value as FrequencyType)}
                className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2 text-xs text-stone-100 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
              >
                <option value="daily" className="bg-stone-950 text-stone-100">Harian (Daily)</option>
                <option value="weekly" className="bg-stone-950 text-stone-100">Mingguan (Weekly)</option>
                <option value="once" className="bg-stone-950 text-stone-100">Sekali Jalan (Once)</option>
              </select>
            </div>
          </div>

          {/* Stat Reward */}
          <div>
            <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-1.5">
              Atribut RPG yang Ditingkatkan
            </label>
            <select
              value={statType}
              onChange={(e) => setStatType(e.target.value as StatType | '')}
              className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl px-3.5 py-2 text-xs text-stone-100 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
            >
              <option value="" className="bg-stone-950 text-stone-100">Tanpa Peningkatan Stat</option>
              <option value="intelligence" className="bg-stone-950 text-stone-100">🧠 Intelligence (Kecerdasan / Belajar)</option>
              <option value="strength" className="bg-stone-950 text-stone-100">🏋️ Strength (Kekuatan / Workout)</option>
              <option value="stamina" className="bg-stone-950 text-stone-100">❤️ Stamina (Daya Tahan / Meditasi / Tidur)</option>
              <option value="agility" className="bg-stone-950 text-stone-100">⚡ Agility (Kelincahan / Lari / Kecepatan)</option>
            </select>
          </div>

          {/* Preview Rewards */}
          <div className="p-3 rounded-xl bg-stone-900/60 border border-stone-800/70 flex items-center justify-between text-xs">
            <span className="text-stone-400">Total Hadiah Saat Selesai:</span>
            <div className="flex items-center gap-2">
              <span className="flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-500/10 border border-sky-500/20 font-mono text-sky-400 font-semibold text-[11px]">
                <Zap className="w-3 h-3" />
                +{expReward} XP
              </span>
              <span className="flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/20 font-mono text-amber-400 font-semibold text-[11px]">
                <Coins className="w-3 h-3" />
                +{goldReward} Gold
              </span>
              {statType && statValue > 0 && (
                <span className="px-2 py-0.5 rounded-md bg-stone-800/80 border border-stone-700/60 text-stone-300 font-mono text-[11px] capitalize font-semibold">
                  +{statValue} {statType}
                </span>
              )}
            </div>
          </div>

          {/* Actions */}
          <div className="flex items-center justify-end gap-2.5 pt-3 border-t border-stone-800/70">
            <button
              type="button"
              onClick={onClose}
              className="px-4 py-2 text-xs font-medium text-stone-400 hover:text-stone-200 transition-colors"
            >
              Batal
            </button>
            <motion.button
              whileTap={{ scale: 0.97 }}
              type="submit"
              disabled={loading || !title.trim()}
              className="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 text-xs font-semibold shadow-gold-sm flex items-center gap-2 disabled:opacity-50 transition-colors"
            >
              {loading ? (
                <>
                  <Loader2 className="w-3.5 h-3.5 animate-spin" />
                  <span>Menyimpan...</span>
                </>
              ) : (
                <span>Simpan Quest</span>
              )}
            </motion.button>
          </div>
        </form>
      </motion.div>
    </div>
  );
};
