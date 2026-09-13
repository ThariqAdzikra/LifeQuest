import React, { createContext, useContext, useEffect, useState } from 'react';
import { User, Session } from '@supabase/supabase-js';
import { supabase, isSupabaseConfigured } from '@/lib/supabase';
import { Profile, calculateLevel } from '@/types';

interface AuthContextType {
  user: User | null;
  profile: Profile | null;
  session: Session | null;
  loading: boolean;
  isAdmin: boolean;
  isDemo: boolean;
  refreshProfile: () => Promise<void>;
  login: (email: string, password: string) => Promise<{ error: Error | null; profile?: Profile | null }>;
  register: (email: string, password: string, name: string, asAdmin?: boolean) => Promise<{ error: Error | null }>;
  logout: () => Promise<void>;
  toggleDemoRole: () => void;
}

const defaultDemoProfile: Profile = {
  id: 'demo-player-id',
  name: 'Hero Player (Demo)',
  email: 'hero@lifequest.app',
  avatar_url: '/images/char.png',
  is_admin: false,
  exp: 340,
  gold: 150,
  intelligence: 18,
  strength: 24,
  stamina: 20,
  agility: 15,
  created_at: new Date().toISOString(),
  updated_at: new Date().toISOString(),
};

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [profile, setProfile] = useState<Profile | null>(null);
  const [session, setSession] = useState<Session | null>(null);
  const [loading, setLoading] = useState(true);
  const [isDemo, setIsDemo] = useState(!isSupabaseConfigured);

  const fetchProfile = async (userId: string) => {
    try {
      const { data, error } = await supabase
        .from('profiles')
        .select('*')
        .eq('id', userId)
        .single();

      if (error) {
        console.error('Error fetching profile:', error);
        return;
      }

      if (data) {
        setProfile({
          ...data,
          level: calculateLevel(data.exp),
        });
      }
    } catch (err) {
      console.error('Unexpected error fetching profile:', err);
    }
  };

  const refreshProfile = async () => {
    if (isDemo && profile) {
      setProfile({
        ...profile,
        level: calculateLevel(profile.exp),
      });
      return;
    }
    if (user?.id) {
      await fetchProfile(user.id);
    }
  };

  useEffect(() => {
    if (!isSupabaseConfigured) {
      // In standalone/demo mode, do NOT auto-login. Only restore explicit session.
      setIsDemo(true);
      try {
        const savedUser = sessionStorage.getItem('lifequest_active_user');
        if (savedUser) {
          const parsed = JSON.parse(savedUser);
          setProfile(parsed);
        } else {
          setProfile(null);
        }
      } catch {
        setProfile(null);
      }
      setLoading(false);
      return;
    }

    supabase.auth.getSession().then(({ data: { session } }) => {
      setSession(session);
      setUser(session?.user ?? null);
      if (session?.user) {
        fetchProfile(session.user.id).finally(() => setLoading(false));
      } else {
        setLoading(false);
      }
    });

    const { data: { subscription } } = supabase.auth.onAuthStateChange(
      async (_event, session) => {
        setSession(session);
        setUser(session?.user ?? null);
        if (session?.user) {
          await fetchProfile(session.user.id);
        } else {
          setProfile(null);
        }
        setLoading(false);
      }
    );

    return () => subscription.unsubscribe();
  }, []);

  const login = async (email: string, password: string) => {
    if (isDemo) {
      // Mock login in demo mode
      const isAdminLogin = email.toLowerCase().includes('admin');
      const mockProfile: Profile = {
        id: isAdminLogin ? 'demo-admin-id' : 'demo-player-id',
        name: isAdminLogin ? 'Administrator' : 'Hero Player',
        email,
        avatar_url: '/images/char.png',
        is_admin: isAdminLogin,
        exp: isAdminLogin ? 5000 : 340,
        gold: isAdminLogin ? 1000 : 150,
        intelligence: 40,
        strength: 35,
        stamina: 30,
        agility: 28,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      };
      const fullProfile = { ...mockProfile, level: calculateLevel(mockProfile.exp) };
      setProfile(fullProfile);
      try {
        sessionStorage.setItem('lifequest_active_user', JSON.stringify(fullProfile));
      } catch {}
      return { error: null, profile: fullProfile };
    }

    const { data, error } = await supabase.auth.signInWithPassword({ email, password });
    if (!error && data?.user) {
      const { data: prof } = await supabase
        .from('profiles')
        .select('*')
        .eq('id', data.user.id)
        .single();
      if (prof) {
        setProfile(prof);
        return { error: null, profile: prof };
      }
    }
    return { error: error ? new Error(error.message) : null, profile: null };
  };

  const register = async (email: string, password: string, name: string, asAdmin = false) => {
    if (isDemo) {
      const mockProfile: Profile = {
        id: 'demo-new-user-id',
        name,
        email,
        avatar_url: '/images/char.png',
        is_admin: asAdmin,
        exp: 0,
        gold: 0,
        intelligence: 10,
        strength: 10,
        stamina: 10,
        agility: 10,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      };
      const fullProfile = { ...mockProfile, level: 1 };
      setProfile(fullProfile);
      try {
        sessionStorage.setItem('lifequest_active_user', JSON.stringify(fullProfile));
      } catch {}
      return { error: null };
    }

    const { error } = await supabase.auth.signUp({
      email,
      password,
      options: {
        data: {
          name,
          is_admin: asAdmin,
        },
      },
    });

    return { error: error ? new Error(error.message) : null };
  };

  const logout = async () => {
    if (isDemo) {
      try {
        sessionStorage.removeItem('lifequest_active_user');
      } catch {}
      setProfile(null);
      return;
    }
    await supabase.auth.signOut();
  };

  const toggleDemoRole = () => {
    if (profile) {
      const newAdminState = !profile.is_admin;
      setProfile({
        ...profile,
        is_admin: newAdminState,
        name: newAdminState ? 'Administrator (Demo)' : 'Hero Player (Demo)',
      });
    }
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        profile,
        session,
        loading,
        isAdmin: Boolean(profile?.is_admin),
        isDemo,
        refreshProfile,
        login,
        register,
        logout,
        toggleDemoRole,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

