export type StatType = 'intelligence' | 'strength' | 'stamina' | 'agility';
export type DifficultyType = 'easy' | 'medium' | 'hard';
export type FrequencyType = 'once' | 'daily' | 'weekly';
export type QuestStatus = 'active' | 'pending_review' | 'completed' | 'rejected';

export interface Profile {
  id: string;
  name: string;
  email: string;
  avatar_url?: string | null;
  is_admin: boolean;
  exp: number;
  gold: number;
  intelligence: number;
  strength: number;
  stamina: number;
  agility: number;
  level?: number;
  created_at: string;
  updated_at: string;
}

export interface Quest {
  id: number;
  title: string;
  description?: string | null;
  difficulty: DifficultyType;
  frequency: FrequencyType;
  exp_reward: number;
  gold_reward: number;
  stat_reward_type?: StatType | null;
  stat_reward_value: number;
  creator_id?: string | null;
  is_admin_quest: boolean;
  is_active: boolean;
  achievement_id?: number | null;
  created_at: string;
  updated_at?: string;
  logs?: QuestLog[];
  achievement?: Achievement | null;
}

export interface QuestLog {
  id: number;
  user_id: string;
  quest_id: number;
  status: QuestStatus;
  submission_file_path?: string | null;
  submission_notes?: string | null;
  admin_notes?: string | null;
  date?: string;
  completed_at?: string | null;
  created_at: string;
  updated_at: string;
  quest?: Quest;
  user?: Profile;
}

export interface Achievement {
  id: number;
  key_name: string;
  title: string;
  description?: string | null;
  icon_path?: string | null;
  exp_reward: number;
  gold_reward: number;
  rarity: 'common' | 'rare' | 'epic' | 'legendary';
  condition?: any;
  created_at: string;
  unlocked_at?: string | null;
}

/**
 * Rumus Leveling LifeQuest persis dari User.php Laravel:
 * level = floor(pow(exp / 100, 0.5)) + 1
 */
export function calculateLevel(exp: number = 0): number {
  if (!exp || exp <= 0) return 1;
  return Math.floor(Math.sqrt(exp / 100)) + 1;
}

/**
 * Menghitung batas EXP untuk level saat ini dan level berikutnya
 */
export function calculateLevelProgress(exp: number = 0) {
  const currentLevel = calculateLevel(exp);
  const currentLevelMinExp = Math.pow(currentLevel - 1, 2) * 100;
  const nextLevelMinExp = Math.pow(currentLevel, 2) * 100;
  
  const expInLevel = Math.max(0, exp - currentLevelMinExp);
  const expNeeded = nextLevelMinExp - currentLevelMinExp;
  const progressPercent = Math.min(100, Math.round((expInLevel / expNeeded) * 100));

  return {
    currentLevel,
    currentExp: exp,
    expInLevel,
    expNeeded,
    nextLevelMinExp,
    progressPercent,
  };
}

export function calculateDefaultRewards(difficulty: DifficultyType, statType?: StatType | null) {
  switch (difficulty) {
    case 'easy':
      return { exp: 10, gold: 5, stat: statType ? 1 : 0 };
    case 'medium':
      return { exp: 100, gold: 15, stat: statType ? 3 : 0 };
    case 'hard':
      return { exp: 1000, gold: 30, stat: statType ? 5 : 0 };
    default:
      return { exp: 10, gold: 5, stat: 0 };
  }
}

