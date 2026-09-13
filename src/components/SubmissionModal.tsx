import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { QuestLog } from '@/types';
import { api } from '@/lib/api';
import { useAuth } from '@/context/AuthContext';
import { X, Upload, FileText, CheckCircle2, Loader2, AlertCircle } from 'lucide-react';

interface SubmissionModalProps {
  log: QuestLog;
  isOpen: boolean;
  onClose: () => void;
  onSuccess: () => void;
}

export const SubmissionModal: React.FC<SubmissionModalProps> = ({
  log,
  isOpen,
  onClose,
  onSuccess,
}) => {
  const { profile } = useAuth();
  const [file, setFile] = useState<File | null>(null);
  const [previewUrl, setPreviewUrl] = useState<string | null>(null);
  const [notes, setNotes] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  if (!isOpen) return null;

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const selected = e.target.files?.[0];
    if (selected) {
      setFile(selected);
      if (selected.type.startsWith('image/')) {
        setPreviewUrl(URL.createObjectURL(selected));
      } else {
        setPreviewUrl(null);
      }
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!file) {
      setError('Harap pilih file bukti pelaksanaan quest (foto/screenshot/dokumen).');
      return;
    }
    if (!profile) return;

    setLoading(true);
    setError(null);

    try {
      // 1. Upload to Supabase Storage
      const uploadedUrl = await api.uploadProofFile(file, profile.id);

      // 2. Submit log with URL
      const res = await api.submitQuestProof(log.id, profile.id, uploadedUrl, notes);
      if (res.error) {
        throw new Error(res.error);
      }

      onSuccess();
      onClose();
    } catch (err: any) {
      setError(err.message || 'Gagal mengirim bukti quest. Silakan coba lagi.');
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
        {/* Close button */}
        <button
          onClick={onClose}
          disabled={loading}
          className="absolute top-4 right-4 p-2 text-stone-500 hover:text-stone-200 rounded-lg hover:bg-stone-800/60 transition-colors"
        >
          <X className="w-5 h-5" />
        </button>

        {/* Header */}
        <div className="mb-5">
          <div className="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold mb-2">
            <Upload className="w-3.5 h-3.5" />
            <span>Pengajuan Bukti Quest</span>
          </div>
          <h2 className="text-xl font-rpg font-semibold text-stone-100 tracking-wide">
            {log.quest?.title || 'Kirim Bukti'}
          </h2>
          <p className="text-xs text-stone-400 mt-1 leading-relaxed">
            Unggah bukti foto atau dokumen untuk direview oleh Admin sebelum menerima EXP & Gold.
          </p>
        </div>

        {error && (
          <div className="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs flex items-start gap-2.5">
            <AlertCircle className="w-4 h-4 flex-shrink-0 mt-0.5 text-rose-400" />
            <span>{error}</span>
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          {/* File input area */}
          <div>
            <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-2">
              File Bukti (Gambar / PDF) *
            </label>
            <div className="relative border-2 border-dashed border-stone-700/70 hover:border-amber-500/50 rounded-xl p-4 text-center cursor-pointer transition-colors bg-stone-900/40">
              <input
                type="file"
                accept="image/*,.pdf,.zip"
                onChange={handleFileChange}
                disabled={loading}
                className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
              />
              {previewUrl ? (
                <div className="flex flex-col items-center gap-2">
                  <img
                    src={previewUrl}
                    alt="Preview"
                    className="max-h-40 rounded-lg object-contain border border-stone-700/70"
                  />
                  <span className="text-xs text-amber-400 font-mono font-medium">{file?.name}</span>
                </div>
              ) : file ? (
                <div className="flex items-center justify-center gap-2 text-stone-300 py-3">
                  <FileText className="w-5 h-5 text-amber-400" />
                  <span className="text-xs font-medium font-mono">{file.name}</span>
                </div>
              ) : (
                <div className="flex flex-col items-center gap-2 py-3 text-stone-400">
                  <Upload className="w-7 h-7 text-stone-500" />
                  <span className="text-xs font-medium text-stone-300">Klik atau seret file bukti ke sini</span>
                  <span className="text-[11px] text-stone-500">Mendukung JPG, PNG, PDF (Maks. 5MB)</span>
                </div>
              )}
            </div>
          </div>

          {/* Notes textarea */}
          <div>
            <label className="block text-xs font-semibold text-stone-300 uppercase tracking-wider mb-2">
              Catatan Pengerjaan (Opsional)
            </label>
            <textarea
              value={notes}
              onChange={(e) => setNotes(e.target.value)}
              disabled={loading}
              placeholder="Ceritakan singkat bagaimana Anda menyelesaikan quest ini..."
              rows={3}
              className="w-full bg-stone-900/80 border border-stone-800/80 rounded-xl p-3 text-xs text-stone-100 placeholder:text-stone-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/20 transition-all"
            />
          </div>

          {/* Action buttons */}
          <div className="flex items-center justify-end gap-2.5 pt-3 border-t border-stone-800/70">
            <button
              type="button"
              onClick={onClose}
              disabled={loading}
              className="px-4 py-2 text-xs font-medium text-stone-400 hover:text-stone-200 transition-colors"
            >
              Batal
            </button>
            <motion.button
              whileTap={{ scale: 0.97 }}
              type="submit"
              disabled={loading || !file}
              className="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 text-xs font-semibold shadow-gold-sm flex items-center gap-2 disabled:opacity-50 transition-colors"
            >
              {loading ? (
                <>
                  <Loader2 className="w-3.5 h-3.5 animate-spin" />
                  <span>Mengirimkan...</span>
                </>
              ) : (
                <>
                  <CheckCircle2 className="w-3.5 h-3.5" />
                  <span>Kirim ke Admin</span>
                </>
              )}
            </motion.button>
          </div>
        </form>
      </motion.div>
    </div>
  );
};
