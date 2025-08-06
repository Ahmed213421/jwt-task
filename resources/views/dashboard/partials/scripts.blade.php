<script src="{{asset('admin/js/jquery.min.js')}}"></script>
<script src="{{asset('admin/js/popper.min.js')}}"></script>
<script src="{{asset('admin/js/moment.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('admin/js/simplebar.min.js')}}"></script>
<script src='{{asset('admin/js/daterangepicker.js')}}'></script>
<script src='{{asset('admin/js/jquery.stickOnScroll.js')}}'></script>
<script src="{{asset('admin/js/tinycolor-min.js')}}"></script>
<script src="{{asset('admin/js/config.js')}}"></script>
<script src="{{asset('admin/js/apps.js')}}"></script>
<script src='{{asset('admin/js/jquery.dataTables.min.js')}}'></script>
<script src='{{asset('admin/js/dataTables.bootstrap4.min.js')}}'></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-56159088-1');
</script>
{{-- <script>
    $(document).ready(function () {
        $('.notification-link').click(function (e) {
            e.preventDefault();

            var notificationId = $(this).data('id'); // Get notification ID
            var notificationItem = $(this);


            $.ajax({
                url: '{{ route("admin.notifications.markAllRead") }}', // Correct route
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    id: notificationId
                },
                success: function (response) {
                    if (response.success) {
                        notificationItem.find('.list-group-item').removeClass('bg-light').addClass('bg-transparent');
                        window.location.href = notificationItem.attr('href');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseJSON?.message || "Unknown error");
                    alert('Error: ' + (xhr.responseJSON?.message || "Something went wrong"));
                }
            });
        });
    });
</script> --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  {{-- <script>



    var channel = pusher.subscribe('channel');
    channel.bind('App\\Events\\NewPostEvent', function(data) {
      console.log(JSON.stringify(data));
       // Update the notification count dynamically
       let unreadCount = parseInt(document.querySelector('.dot.text-success').textContent);
        document.querySelector('.dot.text-success').textContent = unreadCount + 1;

        var notification = data.user; // Assuming the event sends notification data


        var notificationHtml = `
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item bg-light">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="fe fe-box fe-24"></span>
                        </div>
                        <div class="col">
                            <small><strong>'New user Registered'}</strong></small>
                            <div class="my-0 text-muted small">${notification.message}</div>
                            <small class="badge badge-pill badge-light text-muted">Just now</small>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the new notification to the notification list
        var notificationsList = document.querySelector('.list-group');
        notificationsList.insertAdjacentHTML('afterbegin', notificationHtml);

    });
  </script> --}}

@yield('js')
</body>

</html>
