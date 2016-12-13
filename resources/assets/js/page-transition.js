function PageTransition($el) { this.init($el); }

PageTransition.prototype.init = function ($el) {
    var config = {
        prefetch: false,
        onStart: {
            duration: 120,
            render: this.startTransition
        },
        onReady: {
            duration: 120,
            render: this.finishTransition
        }
    };

    $el.smoothState(config).data('smoothState');
};

PageTransition.prototype.startTransition = function ($container) {
    $('#page-content').fadeOut(120);
};

PageTransition.prototype.finishTransition = function ($container, $newContent) {
    $container.html($newContent);
    $('#page-content').fadeIn(120);
    app.run($($newContent).find('[data-module]'));
};
