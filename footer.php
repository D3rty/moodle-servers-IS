            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {

            twemoji.parse(document);

            $('#logout-button').hover(function () {
                $main_text = $(this).text();
                $(this).text("Выйти из аккаунта");
            }, function () {
                $(this).text($main_text);
            });

            $("#sidebar").mCustomScrollbar({
                theme: "minimal",
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');

                let show = ($("#currentDirection").attr('class') == 'animated fadeInDown') ? 'animated fadeOutUp' : 'animated fadeInDown';
                $('#currentDirection').attr('class', show);

                let pic = ($('#sidebarCollapseImg').attr('src') == 'img/chevrons-right.svg') ? 'img/chevrons-left.svg' : 'img/chevrons-right.svg';
                $('#sidebarCollapseImg').attr('src', pic);
            });
        });
    </script>
</body>
</html>