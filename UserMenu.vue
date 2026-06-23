<template>
    <div class="relative inline-block text-left">
        <button @click="isOpen = !isOpen" class="m3-user-btn">
            <img :src="user.avatar" class="avatar" alt="">
            <span>{{ user.name }}</span>
        </button>

        <transition name="m3-menu">
            <div v-if="isOpen" class="m3-menu-container">
                <div class="menu-header">Compte</div>
                <a href="/profile" class="menu-item">
                    <i class="material-icons">person</i> Profil
                </a>
                <a href="/settings" class="menu-item">
                    <i class="material-icons">settings</i> Paramètres
                </a>
                <hr>
                <form action="/logout" method="POST">
                    <button type="submit" class="menu-item text-danger">
                        <i class="material-icons">logout</i> Déconnexion
                    </button>
                </form>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() { return { isOpen: false } },
    mounted() {
        document.addEventListener('click', (e) => {
            if (!this.$el.contains(e.target)) this.isOpen = false;
        });
    }
}</script>

<style scoped>
.m3-user-btn {
    background: var(--m3-primary-container);
    color: var(--m3-on-primary-container);
    border: none;
    padding: 8px 16px;
    border-radius: 28px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    box-shadow: var(--elevation-1); /* Material 3: élévation 1 */
    transition: all 0.2s ease;
}
.m3-user-btn:hover {
    box-shadow: var(--elevation-2); /* Material 3: élévation 2 au survol */
    background: var(--m3-primary-container); /* Material 3: le fond reste le même */
}
.m3-user-btn .avatar {
    width: 32px; /* Material 3: taille d'avatar */
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}
.m3-menu-container {
    position: absolute;
    right: 0;
    top: 50px; /* Ajusté pour être sous le bouton */
    width: 240px; /* Material 3: largeur standard */
    background: var(--m3-surface-container); /* Material 3: surface container */
    border-radius: var(--m3-shape-large); /* Material 3: coins arrondis */
    box-shadow: var(--elevation-3); /* Material 3: élévation 3 */
    padding: 12px;
    z-index: 1000;
    transform-origin: top right;
}
.menu-header {
    font-weight: 500;
    color: var(--m3-on-surface-variant);
    padding: 8px 16px; /* Material 3: padding */
    margin-bottom: 8px;
}
.menu-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: var(--m3-shape-full); /* Material 3: pilule */
    text-decoration: none;
    color: var(--m3-on-surface);
    gap: 12px;
    transition: background-color 0.2s; /* Material 3: transition douce */
}
.menu-item:hover {
    background: var(--m3-surface-container-high); /* Material 3: survol sur surface plus élevée */
}
.menu-item .material-icons {
    font-size: 20px;
}
hr {
    border-top: 1px solid var(--m3-outline);
    margin: 12px 0;
}
.text-danger {
    color: var(--m3-error) !important; /* Surcharge Bootstrap */
}

/* Animations Vue.js (Scale & Fade) */
.m3-menu-enter-active, .m3-menu-leave-active {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); /* Effet de rebond */
}
.m3-menu-enter, .m3-menu-leave-to {
    opacity: 0;
    transform: scale(0.8) translateY(-10px);
}
</style>
