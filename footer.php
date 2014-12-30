    </div><!-- #main -->

    <script src="<?php echo bloginfo('template_url'); ?>/js/min/script.js"></script>

    <?php if (!is_user_logged_in()) { ?>
        <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-35373143-1', 'auto');
        ga('send', 'pageview');

        </script>
	<?php } ?>

<?php wp_footer(); ?>
</body>
</html>
