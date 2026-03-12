<script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    function confirmDelete(e, modalSubmit, callback) {
        var isSubmit = modalSubmit === undefined ? true : false;
        // console.log(isSubmit);
        var self = e;
        var deleteMessage = self.getAttribute('data-confirm') ? self.getAttribute('data-confirm') : 'Delete data\ ?';

        $('#confirm').confirm({
            title: 'Delete Data',
            message: deleteMessage,
            confirm: 'Delete',
            dismiss: 'Back'
        }).on({
            confirm: function () {
                if(isSubmit) {
                    self.submit();
                } else {
                    callback(true);
                }
            },
            dismiss: function () {
                if(isSubmit) {
                    return false;
                } else {
                    callback(false);
                }
            }
        });
    }

    function confirmStatus(message, redirect) {
        $('#confirm').confirm({
            title: 'Ubah Status',
            message: message,
            confirm: 'Yakin',
            dismiss: 'Kembali'
        }).on({
            confirm: function () {
                location.href = redirect;
            },
            dismiss: function () {
                return false;
            }
        });
    }


    function notValidate(message, redirect) {
        var reason = prompt("Alasan Ditolak :", "");
        if (reason === null){
            return;
        } else if (reason != '') {
            $.post(redirect,
            {
                reason: reason
            },
            function(data, status){
                if(data.status)
                    location.reload();
                else
                    alert("Berkas tidak bisa ditolak");
            });
        }
        else
        {
            alert('Mohon isikan alasan berkas ditolak');
        }
    }


    (function ($) {
    $.fn.confirm = function (options) {
        var settings = $.extend({}, $.fn.confirm.defaults, options);

        return this.each(function () {
        var element = this;

        $('.modal-title', this).html(settings.title);
        $('.message', this).html(settings.message);
        $('.confirm', this).html(settings.confirm);
        $('.dismiss', this).html(settings.dismiss);

        $(this).on('click', '.confirm', function (event) {
            $(element).data('confirm', true);
        });

        $(this).on('hide.bs.modal', function (event) {
            if ($(this).data('confirm')) {
            $(this).trigger('confirm', event);
            $(this).removeData('confirm');
            } else {
            $(this).trigger('dismiss', event);
            }

            $(this).off('confirm dismiss');
        });

        $(this).modal('show');
        });
    };

    $.fn.confirm.defaults = {
        title: 'Modal title',
        message: 'One fine body&hellip;',
        confirm: 'OK',
        dismiss: 'Cancel'
    };
    })(jQuery);
</script>