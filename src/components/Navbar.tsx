import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { motion, AnimatePresence } from 'framer-motion';
import { useAuth } from '@/context/AuthContext';
import { calculateLevel } from '@/types';
import {
  Compass,
  Sword,
  Trophy,
  Award,
  ShieldCheck,
  LogOut,
  Menu,
  X,
} from 'lucide-react';

export const Navbar: React.FC = () => {
  const { profile, logout, isDemo, toggleDemoRole, isAdmin } = useAuth();
  const location = useLocation();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  // ─── Unauthenticated ───────────────────────────────────────────────────────
  if (!profile) {
    return (
      <div className="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-3xl">
        <motion.header
          initial={{ y: -16, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          transition={{ duration: 0.4, ease: 'easeOut' }}
        >
          <div className="bg-stone-950/80 backdrop-blur-xl border border-stone-800/70 rounded-2xl px-5 py-3 flex items-center justify-between">
            <Link to="/" className="flex items-center gap-2 group">
              <Sword className="w-4 h-4 text-amber-400" />
              <span className="font-rpg text-sm text-stone-100 tracking-wide">
                Life<span className="text-amber-400">Quest</span>
              </span>
            </Link>
            <div className="flex items-center gap-1.5">
              <Link
                to="/login"
                className="text-xs text-stone-400 hover:text-stone-100 px-3 py-1.5 transition-colors"
              >
                Masuk
              </Link>
              <Link
                to="/register"
                className="text-xs font-semibold px-3.5 py-1.5 rounded-xl bg-amber-500 text-stone-950 hover:bg-amber-400 transition-colors"
              >
                Mulai
              </Link>
            </div>
          </div>
        </motion.header>
      </div>
    );
  }

  // ─── Authenticated ─────────────────────────────────────────────────────────
  const level = profile.level || calculateLevel(profile.exp || 0);

  const navLinks = [
    { name: 'Beranda', path: '/dashboard', icon: Compass },
    { name: 'Quest', path: '/quests', icon: Sword },
    { name: 'Peringkat', path: '/leaderboard', icon: Trophy },
    { name: 'Pencapaian', path: '/achievements', icon: Award },
  ];

  if (isAdmin) {
    navLinks.push({ name: 'Admin', path: '/admin', icon: ShieldCheck });
  }

  return (
    <div className="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-1.5rem)] max-w-4xl">
      <motion.header
        initial={{ y: -16, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.4, ease: 'easeOut' }}
      >
        <div className="bg-stone-950/80 backdrop-blur-xl border border-stone-800/70 rounded-2xl px-4 py-2.5">
          <div className="flex items-center justify-between gap-3">

            {/* Brand */}
            <Link to="/dashboard" className="flex items-center gap-2 group flex-shrink-0">
              <Sword className="w-3.5 h-3.5 text-amber-400" />
              <span className="font-rpg text-sm text-stone-100 tracking-wide hidden sm:inline">
                Life<span className="text-amber-400">Quest</span>
              </span>
            </Link>

            {/* Desktop Nav — centered */}
            <nav className="hidden md:flex items-center gap-0.5 bg-stone-900/60 p-1 rounded-xl border border-stone-800/50">
              {navLinks.map((link) => {
                const Icon = link.icon;
                const isActive = location.pathname.startsWith(link.path);
                return (
                  <Link
                    key={link.path}
                    to={link.path}
                    className={`relative flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors duration-150 ${
                      isActive ? 'text-amber-300' : 'text-stone-500 hover:text-stone-200'
                    }`}
                  >
                    {isActive && (
                      <motion.div
                        layoutId="nav-pill"
                        className="absolute inset-0 bg-amber-500/10 border border-amber-500/20 rounded-lg"
                        transition={{ type: 'spring', stiffness: 400, damping: 30 }}
                      />
                    )}
                    <Icon className="w-3 h-3 relative z-10" />
                    <span className="relative z-10">{link.name}</span>
                  </Link>
                );
              })}
            </nav>

            {/* Right — avatar, level, logout */}
            <div className="flex items-center gap-2">
              {/* Demo indicator — subtle */}
              {isDemo && (
                <button
                  onClick={toggleDemoRole}
                  title={`Demo mode: ${isAdmin ? 'Admin' : 'Player'} — klik untuk ganti`}
                  className="hidden sm:flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] text-stone-500 hover:text-stone-300 border border-stone-800/60 hover:border-stone-700/60 transition-colors"
                >
                  <span className="w-1.5 h-1.5 rounded-full bg-emerald-400" />
                  {isAdmin ? 'Admin' : 'Player'}
                </button>
              )}

              {/* Avatar + level */}
              <div className="relative flex-shrink-0">
                <img
                  src={profile.avatar_url || '/images/char.png'}
                  alt={profile.name}
                  className="w-7 h-7 rounded-full object-cover bg-stone-800 border border-stone-700/60"
                  onError={(e) => { e.currentTarget.src = '/images/char.png'; }}
                />
                <span className="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-amber-500 text-stone-950 text-[9px] font-bold flex items-center justify-center leading-none">
                  {level}
                </span>
              </div>

              {/* Logout */}
              <button
                onClick={logout}
                title="Keluar"
                className="p-1.5 text-stone-600 hover:text-rose-400 hover:bg-rose-500/8 rounded-lg transition-all duration-200"
              >
                <LogOut className="w-3.5 h-3.5" />
              </button>

              {/* Mobile hamburger */}
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="p-1.5 text-stone-500 hover:text-stone-100 md:hidden transition-colors"
              >
                {mobileMenuOpen ? <X className="w-4 h-4" /> : <Menu className="w-4 h-4" />}
              </button>
            </div>
          </div>

          {/* Mobile Menu */}
          <AnimatePresence>
            {mobileMenuOpen && (
              <motion.div
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: 'auto' }}
                exit={{ opacity: 0, height: 0 }}
                transition={{ duration: 0.18 }}
                className="md:hidden overflow-hidden"
              >
                <div className="pt-2.5 pb-1 mt-2 border-t border-stone-800/70 flex flex-col gap-0.5">
                  {navLinks.map((link) => {
                    const Icon = link.icon;
                    const isActive = location.pathname.startsWith(link.path);
                    return (
                      <Link
                        key={link.path}
                        to={link.path}
                        onClick={() => setMobileMenuOpen(false)}
                        className={`flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors ${
                          isActive
                            ? 'bg-amber-500/10 text-amber-300 border border-amber-500/20'
                            : 'text-stone-400 hover:text-stone-200'
                        }`}
                      >
                        <Icon className="w-3.5 h-3.5" />
                        {link.name}
                      </Link>
                    );
                  })}
                  {isDemo && (
                    <button
                      onClick={() => { toggleDemoRole(); setMobileMenuOpen(false); }}
                      className="flex items-center gap-2 px-3 py-2 text-left text-xs text-stone-500 hover:text-stone-300"
                    >
                      <span className="w-1.5 h-1.5 rounded-full bg-emerald-400" />
                      Ganti peran: {isAdmin ? 'Admin → Player' : 'Player → Admin'}
                    </button>
                  )}
                </div>
              </motion.div>
            )}
          </AnimatePresence>
        </div>
      </motion.header>
    </div>
  );
};
