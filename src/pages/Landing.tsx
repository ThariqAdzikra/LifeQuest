import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { ArrowRight, CheckCircle2, Zap, Trophy, Shield, Scroll } from 'lucide-react';

const fadeUp = {
  initial: { opacity: 0, y: 24 },
  animate: { opacity: 1, y: 0 },
};

const stagger = {
  animate: {
    transition: {
      staggerChildren: 0.1,
    },
  },
};

const features = [
  {
    icon: Zap,
    title: 'Sistem EXP & Leveling',
    desc: 'Level karakter dikalkulasi dari akumulasi poin pengalaman. Semakin banyak quest, semakin tinggi level — dengan formula matematis yang transparan.',
    color: 'text-amber-400',
    bg: 'bg-amber-500/8',
    border: 'border-amber-500/20',
  },
  {
    icon: Shield,
    title: 'Verifikasi Bukti Nyata',
    desc: 'Setiap quest resmi memerlukan unggahan bukti yang diverifikasi admin sebelum reward XP diberikan. Tidak ada curang.',
    color: 'text-emerald-400',
    bg: 'bg-emerald-500/8',
    border: 'border-emerald-500/20',
  },
  {
    icon: Trophy,
    title: 'Papan Peringkat',
    desc: 'Bersaing dengan sesama petualang. Peringkat berdasarkan penyelesaian quest dan konsistensi, bukan keberuntungan.',
    color: 'text-rose-400',
    bg: 'bg-rose-500/8',
    border: 'border-rose-500/20',
  },
  {
    icon: Scroll,
    title: 'Quest Harian & Personal',
    desc: 'Admin membuat tantangan komunitas. Kamu buat quest pribadimu sendiri. Keduanya memberikan reward atribut dan gold.',
    color: 'text-sky-400',
    bg: 'bg-sky-500/8',
    border: 'border-sky-500/20',
  },
  {
    icon: CheckCircle2,
    title: 'Achievements & Gelar',
    desc: 'Kumpulkan pencapaian langka untuk menunjukkan dedikasi. Setiap gelar mencerminkan perjalanan nyata, bukan sekedar klik.',
    color: 'text-amber-400',
    bg: 'bg-amber-500/8',
    border: 'border-amber-500/20',
  },
];

export const Landing: React.FC = () => {
  return (
    <div className="min-h-screen text-stone-100 overflow-hidden">
      {/* Ambient glow — subtle warm, NO purple */}
      <div className="pointer-events-none fixed inset-0 overflow-hidden">
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[300px] bg-amber-600/5 rounded-full blur-[120px]" />
        <div className="absolute top-1/3 left-0 w-[400px] h-[400px] bg-rose-900/5 rounded-full blur-[100px]" />
        <div className="absolute top-1/3 right-0 w-[400px] h-[400px] bg-amber-900/5 rounded-full blur-[100px]" />
      </div>

      <main className="relative z-10 w-full max-w-[1400px] mx-auto px-3.5 sm:px-6 lg:px-8">
        {/* Hero */}
        <motion.section
          variants={stagger}
          initial="initial"
          animate="animate"
          className="pt-12 sm:pt-20 pb-16 text-center space-y-7"
        >
          {/* Eyebrow tag */}
          <motion.div variants={fadeUp} transition={{ duration: 0.5 }}>
            <span className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-stone-900/80 border border-stone-700/60 text-stone-400 text-[11px] font-medium tracking-widest uppercase">
              <span className="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse" />
              Productivity RPG
            </span>
          </motion.div>

          {/* Title */}
          <motion.h1
            variants={fadeUp}
            transition={{ duration: 0.6 }}
            className="text-4xl sm:text-5xl lg:text-6xl font-rpg font-bold tracking-wide text-stone-50 leading-tight"
          >
            Setiap Hari{' '}
            <span className="text-amber-400 text-glow-gold">adalah Quest.</span>
          </motion.h1>

          {/* Subtitle */}
          <motion.p
            variants={fadeUp}
            transition={{ duration: 0.6 }}
            className="text-sm sm:text-base text-stone-400 leading-relaxed max-w-lg mx-auto"
          >
            LifeQuest mengubah rutinitas harianmu menjadi petualangan. Selesaikan target nyata, kumpulkan EXP, dan naiki level karaktermu bersama komunitas.
          </motion.p>

          {/* CTA */}
          <motion.div
            variants={fadeUp}
            transition={{ duration: 0.5 }}
            className="flex flex-wrap items-center justify-center gap-3 pt-2"
          >
            <Link
              to="/register"
              className="group flex items-center gap-2 px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 text-sm font-semibold shadow-gold transition-all duration-200"
            >
              <span>Mulai Petualangan</span>
              <ArrowRight className="w-4 h-4 group-hover:translate-x-0.5 transition-transform" />
            </Link>
            <Link
              to="/login"
              className="px-6 py-2.5 rounded-xl bg-stone-900/80 hover:bg-stone-800/80 border border-stone-700/60 text-stone-300 text-sm font-medium transition-all duration-200"
            >
              Masuk Akun
            </Link>
          </motion.div>
        </motion.section>

        {/* Divider */}
        <motion.div
          initial={{ scaleX: 0, opacity: 0 }}
          animate={{ scaleX: 1, opacity: 1 }}
          transition={{ duration: 0.8, delay: 0.4 }}
          className="w-full h-px bg-stone-800/60 relative overflow-hidden"
        >
          <div className="absolute inset-0 bg-gradient-to-r from-transparent via-amber-500/30 to-transparent animate-shimmer" style={{ backgroundSize: '200% 100%' }} />
        </motion.div>

        {/* Features */}
        <section className="py-16">
          <motion.div
            initial={{ opacity: 0, y: 12 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-center mb-12 space-y-2"
          >
            <p className="text-[11px] text-stone-500 tracking-widest uppercase font-medium">Fitur Utama</p>
            <h2 className="text-xl sm:text-2xl font-rpg font-semibold text-stone-200">
              Sistem yang Mendorong Aksi Nyata
            </h2>
          </motion.div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {features.map((feat, i) => {
              const Icon = feat.icon;
              return (
                <motion.div
                  key={feat.title}
                  initial={{ opacity: 0, y: 20 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.4, delay: i * 0.07 }}
                  whileHover={{ y: -3, transition: { duration: 0.2 } }}
                  className={`p-5 rounded-2xl bg-stone-900/50 border ${feat.border} backdrop-blur-sm space-y-3 cursor-default`}
                >
                  <div className={`w-9 h-9 rounded-xl ${feat.bg} border ${feat.border} flex items-center justify-center`}>
                    <Icon className={`w-4.5 h-4.5 ${feat.color}`} />
                  </div>
                  <h3 className="text-sm font-semibold text-stone-100">{feat.title}</h3>
                  <p className="text-xs text-stone-400 leading-relaxed">{feat.desc}</p>
                </motion.div>
              );
            })}
          </div>
        </section>

        {/* Bottom CTA strip */}
        <motion.section
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="mb-16 p-8 rounded-2xl bg-stone-900/60 border border-amber-900/30 text-center space-y-4 relative overflow-hidden"
        >
          <div className="absolute inset-0 bg-amber-500/[0.03]" />
          <p className="text-[11px] text-amber-600/80 tracking-widest uppercase font-medium relative z-10">Bergabung Sekarang</p>
          <h3 className="text-lg sm:text-xl font-rpg font-semibold text-stone-100 relative z-10">
            Siap Memulai Perjalananmu?
          </h3>
          <p className="text-xs text-stone-400 max-w-sm mx-auto relative z-10">
            Daftar gratis, mulai ambil quest, dan lihat bagaimana produktivitasmu berubah menjadi pencapaian nyata.
          </p>
          <div className="relative z-10">
            <Link
              to="/register"
              className="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 text-sm font-semibold shadow-gold transition-all duration-200"
            >
              Daftar Gratis
              <ArrowRight className="w-4 h-4" />
            </Link>
          </div>
        </motion.section>
      </main>
    </div>
  );
};
