import React from 'react';
import { BrowserRouter, Routes, Route, Navigate, useLocation } from 'react-router-dom';
import { AnimatePresence, motion } from 'framer-motion';
import { AuthProvider, useAuth } from '@/context/AuthContext';
import { Sidebar } from '@/components/Sidebar';
import { TopBar } from '@/components/TopBar';
import { Login } from '@/pages/Login';
import { Register } from '@/pages/Register';
import { Dashboard } from '@/pages/Dashboard';
import { Quests } from '@/pages/Quests';
import { Leaderboard } from '@/pages/Leaderboard';
import { Achievements } from '@/pages/Achievements';
import { Admin } from '@/pages/Admin';
import { Loader2 } from 'lucide-react';

const pageVariants = {
  initial: { opacity: 0, y: 12 },
  animate: { opacity: 1, y: 0, transition: { duration: 0.25, ease: [0.25, 0.1, 0.25, 1] as [number, number, number, number] } },
  exit: { opacity: 0, y: -8, transition: { duration: 0.15 } },
};

const AnimatedPage: React.FC<{ children: React.ReactNode }> = ({ children }) => (
  <motion.div
    variants={pageVariants}
    initial="initial"
    animate="animate"
    exit="exit"
    className="flex-1 flex flex-col"
  >
    {children}
  </motion.div>
);

const ProtectedRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { profile, loading } = useAuth();

  if (loading) {
    return (
      <div className="min-h-screen bg-[#0a0a0a] flex flex-col items-center justify-center gap-4">
        <div className="relative">
          <Loader2 className="w-8 h-8 animate-spin text-amber-500/60" />
          <div className="absolute inset-0 blur-lg bg-amber-500/10 rounded-full" />
        </div>
        <span className="text-[11px] text-stone-500 tracking-widest uppercase font-rpg">
          Menyiapkan Karakter...
        </span>
      </div>
    );
  }

  if (!profile) {
    return <Navigate to="/login" replace />;
  }

  return <>{children}</>;
};

const PublicOnlyRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { profile } = useAuth();
  if (profile) {
    return <Navigate to="/dashboard" replace />;
  }
  return <>{children}</>;
};

function AppRoutes() {
  const location = useLocation();
  const { profile } = useAuth();

  return (
    <AnimatePresence mode="wait">
      <Routes location={location} key={location.pathname}>
        <Route
          path="/"
          element={
            <Navigate
              to={profile ? (profile.is_admin ? '/admin' : '/dashboard') : '/login'}
              replace
            />
          }
        />
        <Route
          path="/login"
          element={
            <PublicOnlyRoute>
              <AnimatedPage><Login /></AnimatedPage>
            </PublicOnlyRoute>
          }
        />
        <Route
          path="/register"
          element={
            <PublicOnlyRoute>
              <AnimatedPage><Register /></AnimatedPage>
            </PublicOnlyRoute>
          }
        />
        <Route
          path="/dashboard"
          element={
            <ProtectedRoute>
              {profile?.is_admin ? (
                <Navigate to="/admin" replace />
              ) : (
                <AnimatedPage><Dashboard /></AnimatedPage>
              )}
            </ProtectedRoute>
          }
        />
        <Route
          path="/quests"
          element={<ProtectedRoute><AnimatedPage><Quests /></AnimatedPage></ProtectedRoute>}
        />
        <Route
          path="/leaderboard"
          element={<ProtectedRoute><AnimatedPage><Leaderboard /></AnimatedPage></ProtectedRoute>}
        />
        <Route
          path="/achievements"
          element={<ProtectedRoute><AnimatedPage><Achievements /></AnimatedPage></ProtectedRoute>}
        />
        <Route
          path="/admin"
          element={<ProtectedRoute><AnimatedPage><Admin /></AnimatedPage></ProtectedRoute>}
        />
        <Route
          path="*"
          element={
            <Navigate
              to={profile ? (profile.is_admin ? '/admin' : '/dashboard') : '/login'}
              replace
            />
          }
        />
      </Routes>
    </AnimatePresence>
  );
}

function AppLayout() {
  const { profile } = useAuth();
  const location = useLocation();
  const isAuthPage = location.pathname === '/login' || location.pathname === '/register' || location.pathname === '/';

  const [sidebarOpen, setSidebarOpen] = React.useState<boolean>(() => {
    try {
      const saved = localStorage.getItem('lifequest_sidebar_open');
      return saved !== null ? saved === 'true' : true;
    } catch {
      return true;
    }
  });
  const [mobileOpen, setMobileOpen] = React.useState(false);

  const handleSetSidebarOpen = (open: boolean) => {
    setSidebarOpen(open);
    try {
      localStorage.setItem('lifequest_sidebar_open', String(open));
    } catch {}
  };

  const showNavAndSidebar = profile && !isAuthPage;

  return (
    <div className="min-h-screen flex bg-[#0a0a0a] text-stone-100 selection:bg-amber-500/30 selection:text-amber-200">
      {/* Sidebar for authenticated users on app pages */}
      {showNavAndSidebar && (
        <Sidebar
          isOpen={sidebarOpen}
          setIsOpen={handleSetSidebarOpen}
          mobileOpen={mobileOpen}
          setMobileOpen={setMobileOpen}
        />
      )}

      {/* Main Content Area */}
      <div className="flex-1 min-w-0 min-h-screen flex flex-col">
        {showNavAndSidebar && (
          <TopBar
            sidebarOpen={sidebarOpen}
            setSidebarOpen={handleSetSidebarOpen}
            setMobileOpen={setMobileOpen}
          />
        )}

        <main
          className={`flex-1 flex flex-col ${
            showNavAndSidebar
              ? 'min-h-[calc(100vh-3.5rem)] py-6'
              : 'min-h-screen justify-center'
          }`}
        >
          <AppRoutes />
        </main>
      </div>
    </div>
  );
}

export function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <AppLayout />
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;
