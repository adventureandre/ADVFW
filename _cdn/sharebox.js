$(function () {
    BASE = $('link[rel="base"]').attr('href');

    if ($('.sharebox').length >= 1) {

        //SHARE :: FACEBOOK
        $('.facebook a').click(function () {
            var share = 'https://www.facebook.com/sharer/sharer.php?u=';
            var urlOpen = $(this).attr('href');
            window.open(share + urlOpen, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, width=660, height=400");
            return false;
        });

        //SHARE :: GOOGLE +
        $('.google a').click(function () {
            var share = 'https://plus.google.com/share?url=';
            var urlOpen = $(this).attr('href');
            window.open(share + urlOpen, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, width=516, height=400");
            return false;
        });

        //SHARE :: TWITTER
        $('.twitter a').click(function () {
            var share = 'https://twitter.com/share?url=';
            var urlOpen = $(this).attr('href');
            var complemente = $(this).attr('rel');
            window.open(share + urlOpen + complemente, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, width=660, height=400");
            return false;
        });


        //COUNT :: FACEBOOK
        var facebook = $('.sharebox .facebook a').attr('href');
        $.getJSON('https://graph.facebook.com/?id=' + facebook, function (data) {
            $('.sharebox .facebook .count').text(data.shares);
        });

        //COUNT :: GOOGLE+
        var google = $('.sharebox .google a').attr('href');
        $.post(BASE + '/_cdn/sharebox.php', {url: google}, function (data) {
            $('.sharebox .google .count').text(data);
        });


        //COUNT :: TWITTER
        var twitter = $('.sharebox .twitter a').attr('href');
        $.getJSON('https://cdn.api.twitter.com/1/urls/count.json?url=' + twitter + '/&callback=?', function (data) {
            $('.sharebox .twitter .count').text(data.count);
        });
    }

});
