// Make sure FAB is defined
window.FAB = window.FAB || {};

function runMain (F, W) {
    if (!window.jQuery
        || !F.controller
        || !F.model
    ) {
        setTimeout(() => {
            runMain(F, W);
        }, 1);
    }
}

runMain(window.FAB, window);
