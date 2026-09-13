import React from 'react';
import { Link } from 'react-router-dom';
import { Sword, Code2 } from 'lucide-react';

export const Footer: React.FC = () => {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="mt-auto border-t border-stone-800/70 bg-stone-950/95 backdrop-blur-md relative z-10">
      <div className="w-full max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8 py-3.5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        
        {/* Left: Brand & Creator */}
        <div className="flex items-center gap-2">
          <Link to="/" className="flex items-center gap-1.5 group">
            <Sword className="w-3.5 h-3.5 text-amber-400 group-hover:rotate-12 transition-transform duration-200" />
            <span className="font-rpg text-xs text-stone-200 tracking-wide">
              Life<span className="text-amber-400">Quest</span>
            </span>
          </Link>
          <span className="text-stone-700">·</span>
          <span className="text-[11px] text-stone-400">
            Dibuat oleh{' '}
            <a
              href="https://github.com/ThariqAdzikra"
              target="_blank"
              rel="noopener noreferrer"
              className="text-stone-300 hover:text-amber-400 transition-colors font-medium"
            >
              Muhammad Thariq Adzikra
            </a>
          </span>
        </div>

        {/* Center: Quick Links */}
        <nav className="flex items-center gap-2.5 text-[11px] text-stone-400">
          <Link to="/dashboard" className="hover:text-amber-300 transition-colors">
            Beranda
          </Link>
          <span className="text-stone-800">·</span>
          <Link to="/quests" className="hover:text-amber-300 transition-colors">
            Quest
          </Link>
          <span className="text-stone-800">·</span>
          <Link to="/leaderboard" className="hover:text-amber-300 transition-colors">
            Peringkat
          </Link>
          <span className="text-stone-800">·</span>
          <Link to="/achievements" className="hover:text-amber-300 transition-colors">
            Pencapaian
          </Link>
          <span className="text-stone-800">·</span>
          <Link to="/admin" className="hover:text-amber-300 transition-colors">
            Admin
          </Link>
        </nav>

        {/* Right: Socials Strip & Version */}
        <div className="flex items-center gap-2.5">
          <div className="flex items-center gap-1">
            {/* GitHub */}
            <a
              href="https://github.com/ThariqAdzikra"
              target="_blank"
              rel="noopener noreferrer"
              className="w-7 h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-stone-800/80 hover:border-amber-500/30 flex items-center justify-center text-stone-400 hover:text-amber-300 transition-all shadow-sm"
              title="GitHub (@ThariqAdzikra)"
            >
              <svg className="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                <path fillRule="evenodd" clipRule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
              </svg>
            </a>

            {/* LinkedIn */}
            <a
              href="https://www.linkedin.com/in/muhammad-thariq-adzikra-6b3b7221b/"
              target="_blank"
              rel="noopener noreferrer"
              className="w-7 h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-stone-800/80 hover:border-amber-500/30 flex items-center justify-center text-stone-400 hover:text-amber-300 transition-all shadow-sm"
              title="LinkedIn (Muhammad Thariq Adzikra)"
            >
              <svg className="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.67a1.62 1.62 0 1 0 0 3.24 1.62 1.62 0 0 0 0-3.24z"/>
              </svg>
            </a>

            {/* Instagram */}
            <a
              href="https://www.instagram.com/thrqdz_/"
              target="_blank"
              rel="noopener noreferrer"
              className="w-7 h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-stone-800/80 hover:border-amber-500/30 flex items-center justify-center text-stone-400 hover:text-amber-300 transition-all shadow-sm"
              title="Instagram (@thrqdz_)"
            >
              <svg className="w-3.5 h-3.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
              </svg>
            </a>

            {/* Repository */}
            <a
              href="https://github.com/ThariqAdzikra/LifeQuest"
              target="_blank"
              rel="noopener noreferrer"
              className="w-7 h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-stone-800/80 hover:border-amber-500/30 flex items-center justify-center text-stone-400 hover:text-amber-300 transition-all shadow-sm"
              title="Repositori LifeQuest"
            >
              <Code2 className="w-3.5 h-3.5 text-amber-400" />
            </a>
          </div>

          <span className="text-stone-800 hidden sm:inline">|</span>
          <span className="font-mono text-[10px] text-stone-500 hidden sm:inline">v1.0.0</span>
        </div>

      </div>
    </footer>
  );
};
