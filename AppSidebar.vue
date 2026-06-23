<template>
    <div :class="['m3-navigation-wrapper', { 'is-mobile-open': isMobileOpen }]">
        <!-- Overlay pour mobile -->
        <div class="m3-sidebar-overlay" @click="isMobileOpen = false"></div>

        <aside class="m3-sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <i class="material-icons brand-icon">school</i>
                    <span class="brand-text">INSTI TFE</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div v-for="(item, index) in menuItems" :key="index" class="nav-wrapper">
                    <div v-if="item.isHeader" class="nav-section-title">{{ item.label }}</div>
                    <a v-else
                       :href="item.path"
                       :class="['nav-item', { 'active': currentPath === item.path }]">
                        <div class="active-indicator"></div>
                        <i class="material-icons">{{ item.icon }}</i>
                        <span class="label">{{ item.label }}</span>
                        <span v-if="item.badge" class="badge">{{ item.badge }}</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="footer-divider"></div>
                <p class="version">Version 2.0.0</p>
            </div>
        </aside>

        <!-- Bouton flottant pour ouvrir sur mobile -->
        <button class="mobile-menu-fab" @click="isMobileOpen = true">
            <i class="material-icons">menu</i>
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isMobileOpen: false,
            currentPath: window.location.pathname,
            menuItems: [
                { label: 'Navigation', isHeader: true },
                { label: 'Accueil', icon: 'home', path: '/' },
                { label: 'Explorer', icon: 'explore', path: '/search' },
                { label: 'Gestion', isHeader: true },
                { label: 'Mes documents', icon: 'folder', path: '/my-documents', badge: '3' },
                { label: 'Tableau de bord', icon: 'dashboard', path: '/dashboard' },
                { label: 'Paramètres', icon: 'settings', path: '/settings' },
            ]
        }
    }
}
</script>

<style scoped>
.m3-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: 280px;
    background-color: var(--m3-surface-container-low); /* Material 3: surface container low */
    border-radius: 0 16px 16px 0;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1001;
    padding: 12px;
}

.sidebar-header {
    padding: 16px 12px 28px 16px;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--m3-on-surface);
}

.brand-icon {
    color: var(--m3-primary); /* Material 3: couleur primaire */
    font-size: 32px;
}

.brand-text {
    font-size: 20px;
    font-weight: 500;
    letter-spacing: 0.15px;
}

.sidebar-nav {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.nav-section-title {
    padding: 18px 16px 8px 16px;
    font-size: 12px;
    font-weight: 500;
    color: var(--m3-on-surface-variant);
    text-transform: uppercase;
}

.nav-item {
    position: relative;
    height: 56px;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 16px;
    text-decoration: none;
    color: var(--m3-on-surface-variant);
    border-radius: 28px;
    transition: all 0.2s ease;
}

.nav-item:hover {
    background-color: var(--m3-surface-container-high, #ece6f0);
}

.nav-item.active {
    color: var(--m3-on-secondary-container); /* Material 3: texte sur conteneur secondaire */
    font-weight: 600;
}

.nav-item.active .active-indicator {
    position: absolute;
    left: 0;
    right: 0; /* Material 3: l'indicateur actif prend toute la largeur */
    top: 0;
    bottom: 0;
    background-color: var(--m3-secondary-container); /* Material 3: conteneur secondaire */
    border-radius: 28px;
    z-index: -1;
}

.badge {
    margin-left: auto;
    background: var(--m3-primary);
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
}

/* Responsivité */
@media (max-width: 992px) {
    .m3-sidebar {
        transform: translateX(-100%);
        border-radius: 0;
    }
    .is-mobile-open .m3-sidebar {
        transform: translateX(0);
    }
    .is-mobile-open .m3-sidebar-overlay {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1000;
    }
    .mobile-menu-fab {
        position: fixed;
        bottom: 16px;
        right: 16px;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        border: none;
        box-shadow: var(--elevation-3); /* Material 3: élévation pour FAB */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999;
    }
}

@media (min-width: 993px) {
    .mobile-menu-fab { display: none; }
    .m3-sidebar-overlay { display: none; }
}
</style>
