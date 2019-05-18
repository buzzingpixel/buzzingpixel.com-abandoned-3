import { EventBus } from '../EventBus.js';

export default {
    el: '[ref="SiteNav"]',

    mounted () {
        EventBus.$watch('menuIsOpen', (val) => {
            if (val) {
                this.openMenu();
                return;
            }

            this.closeMenu();
        });
    },

    data () {
        return {
            clickEventInProgress: false,
            isActive: false,
        };
    },

    methods: {
        expanderClick () {
            const shouldOpen = !EventBus.menuIsOpen;

            this.clickEventInProgress = true;

            EventBus.menuIsOpen = shouldOpen;

            setTimeout(() => {
                this.clickEventInProgress = shouldOpen;
            }, 500);
        },

        mouseEnter () {
            setTimeout(() => {
                if (this.clickEventInProgress) {
                    return;
                }

                EventBus.menuIsOpen = true;
            }, 50);
        },

        mouseLeave () {
            setTimeout(() => {
                if (this.clickEventInProgress) {
                    return;
                }

                EventBus.menuIsOpen = false;
            }, 400);
        },

        openMenu () {
            this.isActive = true;
        },

        closeMenu () {
            this.isActive = false;
        },
    },
};
