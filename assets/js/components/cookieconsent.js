window.cookieconsent.initialise({
    palette: {
        popup: {
            background: '#efefef',
            text: '#404040'
        },
        button: {
            background: '#8ec760',
            text: '#ffffff'
        }
    },
    theme: 'edgeless',
    position: 'bottom-right',
    type: 'opt-in',
    content: {
        href: '/privacy'
    },
    onInitialise: function (status) {
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-42602973-3', 'auto');
        ga('set', 'forceSSL', true);

        if (this.hasConsented()) {
            ga('require', 'displayfeatures');
            ga('set', 'anonymizeIp', undefined);
        } else {
            ga('set', 'displayFeaturesTask', null);
            ga('set', 'anonymizeIp', true);
        }

        ga('send', 'pageview');
    },
    onStatusChange: function (status, chosenBefore) {
        if (this.hasConsented()) {
            const storageItem = localStorage.getItem('hasConsented');
            const storageParams = {
                hasConsented: true,
                timestamp: (new Date).getTime()
            };

            localStorage.setItem('hasConsented', JSON.stringify(storageParams));
            localStorage.setItem('setConsentedCookie', 'yes');

            window.location.reload();
        }
    }
});
