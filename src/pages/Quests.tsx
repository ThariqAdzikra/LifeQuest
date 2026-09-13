import React, { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/lib/api';
import { Quest, QuestLog } from '@/types';
import { QuestCard } from '@/components/QuestCard';
import { SubmissionModal } from '@/components/SubmissionModal';
import { CreateQuestModal } from '@/components/CreateQuestModal';
import { 
  Plus, 
  Loader2,
  CheckCircle2,
  Scroll,
  Swords,
  Clock,
  Sparkles,
  ShieldAlert
} from 'lucide-react';

const containerVariants = {
  hidden: {},
  show: {
    transition: {
      staggerChildren: 0.06,
    },
  },
};

export const Quests: React.FC = () => {
  const { profile, refreshProfile } = useAuth();
  const [activeTab, setActiveTab] = useState<'my' | 'admin' | 'personal' | 'completed' | 'manage'>('my');
  const [questsData, setQuestsData] = useState<{
    myQuests: QuestLog[];
    adminQuests: Quest[];
    personalQuests: Quest[];
    completedQuests: QuestLog[];
    myTemplates: Quest[];
  }>({
    myQuests: [],
    adminQuests: [],
    personalQuests: [],
    completedQuests: [],
    myTemplates: [],
  });
  const [loading, setLoading] = useState(true);
  const [selectedLogForSubmission, setSelectedLogForSubmission] = useState<QuestLog | null>(null);
  const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
  const [toastMessage, setToastMessage] = useState<string | null>(null);

  const showToast = (msg: string) => {
    setToastMessage(msg);
    setTimeout(() => setToastMessage(null), 3500);
  };

  const loadData = async () => {
    if (!profile?.id) return;
    setLoading(true);
    try {
      const data = await api.getQuests(profile.id);
      setQuestsData(data);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, [profile?.id]);

  const handleTakeQuest = async (questId: number) => {
    if (!profile) return;
    const res = await api.takeQuest(questId, profile.id);
    if (res.error) {
      showToast(res.error);
    } else {
      showToast('Quest berhasil diambil.');
      loadData();
      setActiveTab('my');
    }
  };

  const handleCompletePersonal = async (logId: number) => {
    if (!profile) return;
    const res = await api.completePersonalQuest(logId, profile.id);
    if (res.error) {
      showToast(res.error);
    } else {
      showToast('Quest selesai! EXP & Gold telah bertambah.');
      await refreshProfile();
      loadData();
    }
  };

  const handleCancelQuest = async (logId: number) => {
    if (!profile) return;
    const res = await api.cancelQuest(logId, profile.id);
    if (res.error) {
      showToast(res.error);
    } else {
      showToast('Quest dibatalkan.');
      loadData();
    }
  };

  const tabs = [
    { id: 'my', label: 'Quest Saya', count: questsData.myQuests.length },
    { id: 'admin', label: 'Tantangan Admin', count: questsData.adminQuests.length },
    { id: 'personal', label: 'Quest Pribadi', count: questsData.personalQuests.length },
    { id: 'completed', label: 'Riwayat Selesai', count: questsData.completedQuests.length },
    { id: 'manage', label: 'Kelola Template', count: questsData.myTemplates.length },
  ];

  return (
    <div className="w-full max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
      {/* Toast Notification */}
      <AnimatePresence>
        {toastMessage && (
          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: 16 }}
            className="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-xl bg-stone-900/95 border border-stone-800 text-stone-100 text-xs shadow-dark-lg backdrop-blur-md flex items-center gap-2.5"
          >
            <CheckCircle2 className="w-4 h-4 text-emerald-400 flex-shrink-0" />
            <span>{toastMessage}</span>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Header & Create Button */}
      <motion.div
        initial={{ opacity: 0, y: 10 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.35 }}
        className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
      >
        <div>
          <h1 className="text-xl sm:text-2xl font-rpg font-semibold text-stone-50 tracking-wide">
            Papan Quest
          </h1>
          <p className="text-xs text-stone-500 mt-1">
            Pilih target produktivitasmu, unggah bukti pencapaian, dan kumpulkan reward.
          </p>
        </div>

        <motion.button
          whileTap={{ scale: 0.97 }}
          onClick={() => setIsCreateModalOpen(true)}
          className="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 text-xs font-semibold shadow-gold-sm flex items-center gap-1.5 transition-colors duration-200"
        >
          <Plus className="w-3.5 h-3.5" />
          <span>Buat Quest Kustom</span>
        </motion.button>
      </motion.div>

      {/* RPG Segmented Tabs */}
      <motion.div
        initial={{ opacity: 0, y: 8 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.35, delay: 0.1 }}
        className="flex items-center gap-1.5 overflow-x-auto p-1 rounded-xl bg-stone-900/60 border border-stone-800/60 backdrop-blur-sm scrollbar-none"
      >
        {tabs.map((tab) => {
          const isActive = activeTab === tab.id;
          return (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id as any)}
              className={`flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-medium transition-all duration-150 whitespace-nowrap ${
                isActive
                  ? 'bg-amber-500/15 border border-amber-500/30 text-amber-300 shadow-sm'
                  : 'text-stone-400 hover:text-stone-200 hover:bg-stone-800/50 border border-transparent'
              }`}
            >
              <span>{tab.label}</span>
              <span
                className={`text-[10px] px-1.5 py-0.5 rounded-md font-mono transition-colors ${
                  isActive
                    ? 'bg-amber-500/25 text-amber-300 border border-amber-500/30'
                    : 'bg-stone-800/80 text-stone-500 border border-stone-700/50'
                }`}
              >
                {tab.count}
              </span>
            </button>
          );
        })}
      </motion.div>

      {/* Tab Grid Content */}
      {loading ? (
        <div className="py-20 flex flex-col items-center justify-center gap-3 text-stone-500">
          <Loader2 className="w-6 h-6 animate-spin text-amber-500/60" />
          <span className="text-xs font-rpg tracking-wider uppercase">Memuat daftar quest...</span>
        </div>
      ) : (
        <motion.div
          variants={containerVariants}
          initial="hidden"
          animate="show"
          key={activeTab}
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
        >
          {/* Tab 1: My Quests */}
          {activeTab === 'my' && (
            <>
              {questsData.myQuests.length === 0 ? (
                <div className="col-span-full py-16 px-4 text-center rounded-2xl bg-stone-900/40 border border-stone-800/60 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                  <div className="w-10 h-10 rounded-xl bg-stone-800/60 border border-stone-700/60 flex items-center justify-center text-stone-400">
                    <Scroll className="w-5 h-5 text-amber-400/80" />
                  </div>
                  <p className="text-xs text-stone-400 max-w-sm mt-1">
                    Kamu belum mengambil quest aktif. Buka tab <strong className="text-amber-400 font-semibold">Tantangan Admin</strong> atau buat quest kustom untuk memulai.
                  </p>
                </div>
              ) : (
                questsData.myQuests.map((log) => (
                  <QuestCard
                    key={log.id}
                    log={log}
                    onSubmitProof={(targetLog) => setSelectedLogForSubmission(targetLog)}
                    onComplete={handleCompletePersonal}
                    onCancel={handleCancelQuest}
                  />
                ))
              )}
            </>
          )}

          {/* Tab 2: Admin Quests */}
          {activeTab === 'admin' && (
            <>
              {questsData.adminQuests.length === 0 ? (
                <div className="col-span-full py-16 px-4 text-center rounded-2xl bg-stone-900/40 border border-stone-800/60 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                  <div className="w-10 h-10 rounded-xl bg-stone-800/60 border border-stone-700/60 flex items-center justify-center text-stone-400">
                    <Swords className="w-5 h-5 text-amber-400/80" />
                  </div>
                  <p className="text-xs text-stone-400 max-w-sm mt-1">
                    Semua tantangan resmi admin saat ini sedang aktif atau telah diselesaikan.
                  </p>
                </div>
              ) : (
                questsData.adminQuests.map((quest) => (
                  <QuestCard key={quest.id} quest={quest} onTake={handleTakeQuest} />
                ))
              )}
            </>
          )}

          {/* Tab 3: Personal Quests */}
          {activeTab === 'personal' && (
            <>
              {questsData.personalQuests.length === 0 ? (
                <div className="col-span-full py-16 px-4 text-center rounded-2xl bg-stone-900/40 border border-stone-800/60 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                  <div className="w-10 h-10 rounded-xl bg-stone-800/60 border border-stone-700/60 flex items-center justify-center text-stone-400">
                    <Sparkles className="w-5 h-5 text-amber-400/80" />
                  </div>
                  <p className="text-xs text-stone-400 max-w-sm mt-1">
                    Belum ada quest pribadi. Klik tombol <strong className="text-amber-400 font-semibold">Buat Quest Kustom</strong> di atas untuk merancang kebiasaanmu.
                  </p>
                </div>
              ) : (
                questsData.personalQuests.map((quest) => (
                  <QuestCard key={quest.id} quest={quest} onTake={handleTakeQuest} />
                ))
              )}
            </>
          )}

          {/* Tab 4: Completed Quests */}
          {activeTab === 'completed' && (
            <>
              {questsData.completedQuests.length === 0 ? (
                <div className="col-span-full py-16 px-4 text-center rounded-2xl bg-stone-900/40 border border-stone-800/60 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                  <div className="w-10 h-10 rounded-xl bg-stone-800/60 border border-stone-700/60 flex items-center justify-center text-stone-400">
                    <Clock className="w-5 h-5 text-amber-400/80" />
                  </div>
                  <p className="text-xs text-stone-400 max-w-sm mt-1">
                    Belum ada riwayat quest yang selesai. Selesaikan quest untuk mencatatkan riwayat di sini.
                  </p>
                </div>
              ) : (
                questsData.completedQuests.map((log) => (
                  <QuestCard key={log.id} log={log} />
                ))
              )}
            </>
          )}

          {/* Tab 5: Manage Templates */}
          {activeTab === 'manage' && (
            <>
              {questsData.myTemplates.length === 0 ? (
                <div className="col-span-full py-16 px-4 text-center rounded-2xl bg-stone-900/40 border border-stone-800/60 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                  <div className="w-10 h-10 rounded-xl bg-stone-800/60 border border-stone-700/60 flex items-center justify-center text-stone-400">
                    <Scroll className="w-5 h-5 text-amber-400/80" />
                  </div>
                  <p className="text-xs text-stone-400 max-w-sm mt-1">
                    Belum ada template quest berulang (Harian / Mingguan). Quest pribadi berulang akan otomatis tampil di sini.
                  </p>
                </div>
              ) : (
                questsData.myTemplates.map((quest) => (
                  <QuestCard
                    key={quest.id}
                    quest={quest}
                    onToggleStatus={() => {
                      showToast('Status quest template diperbarui.');
                    }}
                    onDelete={() => {
                      showToast('Quest template dihapus.');
                    }}
                  />
                ))
              )}
            </>
          )}
        </motion.div>
      )}

      {/* Modals */}
      {selectedLogForSubmission && (
        <SubmissionModal
          log={selectedLogForSubmission}
          isOpen={Boolean(selectedLogForSubmission)}
          onClose={() => setSelectedLogForSubmission(null)}
          onSuccess={() => {
            showToast('Bukti berhasil dikirim untuk direview admin.');
            loadData();
          }}
        />
      )}

      <CreateQuestModal
        isOpen={isCreateModalOpen}
        onClose={() => setIsCreateModalOpen(false)}
        onSuccess={() => {
          showToast('Quest kustom berhasil dibuat.');
          loadData();
        }}
      />
    </div>
  );
};
