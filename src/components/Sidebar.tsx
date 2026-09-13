import React from 'react';
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
  PanelLeftClose,
  X,
} from 'lucide-react';

interface SidebarProps {
  isOpen: boolean;
  setIsOpen: (open: boolean) => void;
  mobileOpen: boolean;
  setMobileOpen: (open: boolean) => void;
}

export const Sidebar: React.FC<SidebarProps> = ({
  isOpen,
  setIsOpen,
  mobileOpen,
  setMobileOpen,
}) => {
  const { profile, logout, isDemo, toggleDemoRole, isAdmin } = useAuth();
  const location = useLocation();

  if (!profile) return null;

  const level = profile.level || calculateLevel(profile.exp || 0);

  const homePath = isAdmin ? '/admin' : '/dashboard';

  const navLinks = [
    { name: 'Beranda', path: homePath, icon: Compass },
    { name: 'Quest', path: '/quests', icon: Sword },
    { name: 'Peringkat', path: '/leaderboard', icon: Trophy },
    { name: 'Pencapaian', path: '/achievements', icon: Award },
  ];

  const renderContent = (isMobile: boolean) => (
    <div className="flex flex-col h-full justify-between select-none">
      {/* Top Header & Navigation */}
      <div>
        {/* Brand Header */}
        <div className="h-14 px-4 flex items-center justify-between border-b border-stone-800/60">
          <Link
            to={homePath}
            onClick={() => isMobile && setMobileOpen(false)}
            className="flex items-center gap-2 group"
          >
            <Sword className="w-4 h-4 text-amber-400 group-hover:rotate-12 transition-transform duration-200" />
            <span className="font-rpg text-sm font-semibold tracking-wider text-stone-100">
              Life<span className="text-amber-400">Quest</span>
            </span>
          </Link>

          {/* Close/Hide Button */}
          <button
            onClick={() => (isMobile ? setMobileOpen(false) : setIsOpen(false))}
            className="p-1.5 rounded-lg text-stone-500 hover:text-stone-300 hover:bg-stone-900 transition-colors"
            title="Tutup Sidebar"
          >
            {isMobile ? <X className="w-4 h-4" /> : <PanelLeftClose className="w-4 h-4" />}
          </button>
        </div>

        {/* Navigation Items */}
        <nav className="p-3 space-y-1">
          {navLinks.map((link) => {
            const Icon = link.icon;
            const isActive = location.pathname.startsWith(link.path);
            return (
              <Link
                key={link.path}
                to={link.path}
                onClick={() => isMobile && setMobileOpen(false)}
                className={`flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors ${
                  isActive
                    ? 'bg-amber-500/10 text-amber-400 font-semibold'
                    : 'text-stone-400 hover:text-stone-200 hover:bg-stone-900/60'
                }`}
              >
                <Icon className={`w-4 h-4 ${isActive ? 'text-amber-400' : 'text-stone-500'}`} />
                <span>{link.name}</span>
              </Link>
            );
          })}
        </nav>
      </div>

      {/* Bottom Profile & Actions (No footer) */}
      <div className="p-3 border-t border-stone-800/60">
        {/* Minimal User Row + Logout */}
        <div className="flex items-center justify-between px-2 py-1.5 rounded-lg bg-stone-900/30 border border-stone-800/40">
          <div className="flex items-center gap-2.5 min-w-0">
            <div className="relative flex-shrink-0">
              <img
                src={profile.avatar_url || '/images/char.png'}
                alt={profile.name}
                className="w-7 h-7 rounded-md object-cover bg-stone-800 border border-stone-700/60"
                onError={(e) => {
                  e.currentTarget.src = '/images/char.png';
                }}
              />
              <span className="absolute -bottom-1 -right-1 px-1 rounded bg-amber-500 text-stone-950 text-[8px] font-bold font-mono leading-none">
                {level}
              </span>
            </div>
            <div className="min-w-0">
              <p className="text-xs font-medium text-stone-200 truncate">{profile.name}</p>
              <p className="text-[10px] text-stone-500 font-mono capitalize">
                {isAdmin ? 'Admin' : 'Petualang'} · {profile.gold || 0}g
              </p>
            </div>
          </div>

          <button
            onClick={logout}
            title="Keluar"
            className="p-1.5 rounded-md text-stone-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors flex-shrink-0 ml-1"
          >
            <LogOut className="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>
  );

  return (
    <>
      {/* Desktop Sidebar (No scrollbar, completely overflow-hidden) */}
      <aside
        className={`hidden md:flex flex-col flex-shrink-0 border-r border-stone-800/80 bg-stone-950/98 backdrop-blur-xl transition-all duration-300 ease-in-out sticky top-0 h-screen z-40 overflow-hidden ${
          isOpen ? 'w-56 opacity-100' : 'w-0 opacity-0 overflow-hidden border-r-0 pointer-events-none'
        }`}
      >
        <div className="w-56 h-full flex flex-col justify-between overflow-hidden">
          {renderContent(false)}
        </div>
      </aside>

      {/* Mobile Drawer Backdrop */}
      <AnimatePresence>
        {mobileOpen && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.2 }}
            onClick={() => setMobileOpen(false)}
            className="md:hidden fixed inset-0 bg-black/70 backdrop-blur-xs z-50"
          />
        )}
      </AnimatePresence>

      {/* Mobile Sidebar Drawer (No scrollbar, overflow-hidden) */}
      <div
        className={`md:hidden fixed inset-y-0 left-0 z-50 w-64 h-full bg-stone-950 border-r border-stone-800/90 shadow-2xl transform transition-transform duration-300 ease-in-out overflow-hidden flex flex-col justify-between ${
          mobileOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        {renderContent(true)}
      </div>
    </>
  );
};
