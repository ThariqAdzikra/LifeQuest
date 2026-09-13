import React from 'react';
import { useLocation } from 'react-router-dom';
import { useAuth } from '@/context/AuthContext';
import { calculateLevel } from '@/types';
import {
  PanelLeftClose,
  PanelLeftOpen,
  Sword,
  Coins,
  ChevronRight,
} from 'lucide-react';

interface TopBarProps {
  sidebarOpen: boolean;
  setSidebarOpen: (open: boolean) => void;
  setMobileOpen: (open: boolean) => void;
}

export const TopBar: React.FC<TopBarProps> = ({
  sidebarOpen,
  setSidebarOpen,
  setMobileOpen,
}) => {
  const { profile } = useAuth();
  const location = useLocation();

  if (!profile) return null;

  const level = profile.level || calculateLevel(profile.exp || 0);

  const getPageTitle = () => {
    if (location.pathname.startsWith('/dashboard') || location.pathname.startsWith('/admin')) return 'Beranda';
    if (location.pathname.startsWith('/quests')) return 'Quest';
    if (location.pathname.startsWith('/leaderboard')) return 'Peringkat';
    if (location.pathname.startsWith('/achievements')) return 'Pencapaian';
    return 'LifeQuest';
  };

  const pageTitle = getPageTitle();

  const handleToggle = () => {
    if (window.innerWidth < 768) {
      setMobileOpen(true);
    } else {
      setSidebarOpen(!sidebarOpen);
    }
  };

  return (
    <header className="sticky top-0 z-30 h-14 bg-stone-950/80 backdrop-blur-md border-b border-stone-800/60 px-3.5 sm:px-6 lg:px-8 flex items-center justify-between select-none">
      {/* Left: Sidebar Toggle & Clean Breadcrumb */}
      <div className="flex items-center gap-3">
        {/* Toggle Button */}
        <button
          onClick={handleToggle}
          className="p-2 rounded-lg bg-stone-900/60 hover:bg-stone-800 border border-stone-800/80 hover:border-amber-500/30 text-stone-400 hover:text-amber-400 transition-colors"
          title={sidebarOpen ? 'Sembunyikan Sidebar' : 'Buka Sidebar'}
        >
          {sidebarOpen ? (
            <PanelLeftClose className="w-4 h-4" />
          ) : (
            <PanelLeftOpen className="w-4 h-4 text-amber-400" />
          )}
        </button>

        {/* Mobile Brand (visible only when screen < md) */}
        <div className="flex items-center gap-1.5 md:hidden">
          <Sword className="w-3.5 h-3.5 text-amber-400" />
          <span className="font-rpg text-xs text-stone-200 font-bold tracking-wider">
            Life<span className="text-amber-400">Quest</span>
          </span>
        </div>

        {/* Breadcrumb */}
        <div className="hidden sm:flex items-center gap-2 text-stone-600">
          <ChevronRight className="w-3.5 h-3.5 text-stone-700" />
          <span className="font-rpg text-xs font-medium text-stone-300 tracking-wide">
            {pageTitle}
          </span>
        </div>
      </div>

      {/* Right: Quick Stats & Avatar */}
      <div className="flex items-center gap-2.5">
        {/* Gold Counter */}
        <div className="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-stone-900/60 border border-stone-800/70 text-xs font-mono text-amber-300">
          <Coins className="w-3.5 h-3.5 text-amber-400" />
          <span>{profile.gold || 0}</span>
        </div>

        {/* Avatar + Level */}
        <div className="flex items-center gap-2 pl-2 border-l border-stone-800/70">
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
          <span className="text-xs font-medium text-stone-300 hidden md:inline truncate max-w-[130px]">
            {profile.name}
          </span>
        </div>
      </div>
    </header>
  );
};
