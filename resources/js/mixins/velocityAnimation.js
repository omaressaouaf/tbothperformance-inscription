import Velocity from "velocity-animate";

export default {
    methods: {
        slideUp(el, done) {
            Velocity(el, "slideUp", { duration: 300 }, { complete: done });
        },
        slideDown(el, done) {
            Velocity(el, "slideDown", { duration: 300 }, { complete: done });
        },
        fadeIn(el, done) {
            Velocity(el, "fadeIn", { duration: 300 }, { complete: done });
        },
        fadeOut(el, done) {
            Velocity(el, "fadeOut", { duration: 300 }, { complete: done });
        },
    },
};
