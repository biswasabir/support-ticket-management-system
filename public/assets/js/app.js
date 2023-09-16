(function($) {

    "use strict";

    document.querySelectorAll('[data-year]').forEach(function(el) {
        el.textContent = new Date().getFullYear();
    });

    if ($('[data-aos]').length > 0) {
        AOS.init({
            once: true
        });
    }

    var dropdown = document.querySelectorAll('[data-dropdown]');
    if (dropdown != null) {
        dropdown.forEach(function(el) {
            let dropdownMenu = el.querySelector(".drop-down-menu");

            function dropdownOP() {
                if (el.getBoundingClientRect().top + dropdownMenu.offsetHeight > window.innerHeight - 60 && el.getAttribute("data-dropdown-position") !== "top") {
                    dropdownMenu.style.top = "auto";
                    dropdownMenu.style.bottom = "40px";
                } else {
                    dropdownMenu.style.top = "40px";
                    dropdownMenu.style.bottom = "auto";
                }
            }
            window.addEventListener("click", function(e) {
                if (el.contains(e.target)) {
                    el.classList.toggle('active');
                    setTimeout(function() {
                        el.classList.toggle('animated');
                    }, 0);
                } else {
                    el.classList.remove('active');
                    el.classList.remove('animated');
                }
                dropdownOP();
            });
            window.addEventListener("resize", dropdownOP);
            window.addEventListener("scroll", dropdownOP);
        });
    }

    let navbar = document.querySelector(".nav-bar");
    if (navbar) {
        let navbarOp = () => {
            if (window.scrollY > 0) {
                navbar.classList.add("scrolling");
            } else {
                navbar.classList.remove("scrolling");
            }
        };
        window.addEventListener("scroll", navbarOp);
        window.addEventListener("load", navbarOp);
    }

    let navbarMenu = document.querySelector(".nav-bar-menu"),
        navbarMenuBtn = document.querySelector(".nav-bar-menu-btn");
    if (navbarMenu) {
        let navbarMenuClose = navbarMenu.querySelector(".nav-bar-menu-close"),
            navbarMenuOverlay = navbarMenu.querySelector(".overlay"),
            navUploadBtn = document.querySelector(".nav-bar-menu [data-upload-btn]");
        navbarMenuBtn.onclick = () => {
            navbarMenu.classList.add("show");
            document.body.classList.add("overflow-hidden");
        };

        navbarMenuClose.onclick = navbarMenuOverlay.onclick = () => {
            navbarMenu.classList.remove("show");
            document.body.classList.remove("overflow-hidden");
        };
        if (navUploadBtn) {
            navUploadBtn.addEventListener("click", () => {
                navbarMenu.classList.remove("show");
            });
        }
    }

    const dashSidebar = document.querySelector(".dash-sidebar"),
        dashSidebarToggle = document.querySelectorAll(".dash-sidebar-toggle");
    if (dashSidebar) {
        dashSidebarToggle.forEach((el) => {
            el.addEventListener("click", () => {
                document.querySelector(".dash").classList.toggle("toggle");
            });
        });
        dashSidebar.querySelector(".overlay").addEventListener("click", () => {
            document.querySelector(".dash").classList.remove("toggle");
        });
    }

    let pageDoc = document.querySelector(".page-doc"),
        sidebarToggle = document.querySelector(".sidebar-toggle");
    if (sidebarToggle) {
        sidebarToggle.onclick = () => {
            if (sidebarToggle.classList.contains("active")) {
                pageDoc.classList.remove("toggle");
                sidebarToggle.classList.remove("active");
            } else {
                pageDoc.classList.add("toggle");
                sidebarToggle.classList.add("active");
            }
        };
        window.addEventListener("resize", () => {
            pageDoc.classList.remove("toggle");
            sidebarToggle.classList.remove("active");
        });
    }

    const docSearchBtn = document.querySelector(".search-btn"),
        docSearch = document.querySelector(".nav-search"),
        docSearchClose = document.querySelector(".search-close");
    if (docSearch) {
        docSearchBtn.addEventListener("click", () => {
            docSearch.classList.add("active");
        });
        docSearchClose.addEventListener("click", () => {
            docSearch.classList.remove("active");
            docSearch.querySelector("input").value = "";
            docSearch.classList.remove("show");
        });
    }


    let actionConfirm = $('.action-confirm');
    if (actionConfirm.length) {
        actionConfirm.on('click', function(e) {
            if (!confirm(config.translates.actionConfirm)) {
                e.preventDefault();
            }
        });
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let search = $(".search"),
        searchInput = $(".search .search-input input"),
        searchResults = $('.search-results div');

    searchInput.on('input', function() {
        if (this.value.length > 0) {
            search.addClass("show");
            $.ajax({
                url: config.url + '/knowledgebase/search',
                type: 'POST',
                data: { q: $(this).val(), },
                success: function(response) {
                    searchResults.html(response);
                },
            });
        } else
            search.removeClass("show");
    });

    let clipboardBtn = document.querySelectorAll(".btn-copy");
    if (clipboardBtn) {
        clipboardBtn.forEach((el) => {
            let clipboard = new ClipboardJS(el);
            clipboard.on("success", () => {
                toastr.success(config.translates.copied);
            });
        });
    }


    let inputNumeric = document.querySelectorAll('.input-numeric');
    if (inputNumeric) {
        inputNumeric.forEach((el) => {
            el.oninput = () => {
                el.value = el.value.replace(/[^0-9]/g, '').substr(0, 6);
            };
        });
    }

    let cookies = document.querySelector('.cookies');
    if (cookies) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                cookies.classList.add('show');
            }, 1000);
        });
    }

    let acceptCookie = $('#acceptCookie'),
        cookieDiv = $('.cookies');
    acceptCookie.on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: config.url + '/cookie/accept',
            type: 'get',
            dataType: "JSON",
            success: function(response) {
                if ($.isEmptyObject(response.error)) {
                    cookieDiv.remove();
                    toastr.success(response.success);
                }
            },
        });
    });

    let loadModalBtn = document.querySelector('#loadModalBtn');
    if (loadModalBtn) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                loadModalBtn.click();
                loadModalBtn.remove();
            }, 1000);
        });
        document.querySelector('#load-modal .btn-close').onclick = () => {
            $.ajax({
                url: config.url + '/popup/close',
                type: 'get',
                dataType: "JSON",
                success: function() {
                    $('load-modal').remove();
                },
            });
        };
    }


    let SelectImageButton = $('.select-image-button');
    SelectImageButton.on('click', function() {
        var dataId = $(this).data('id');
        let targetedImageInput = $('#image-targeted-input-' + dataId),
            targetedImagePreview = $('#preview-img-' + dataId);
        targetedImageInput.trigger('click');
        targetedImageInput.on('change', function() {
            var file = true,
                readImageURL;
            if (file) {
                readImageURL = function(input_file) {
                    if (input_file.files && input_file.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            targetedImagePreview.attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input_file.files[0]);
                    }
                }
            }
            readImageURL(this);
        });
    });

    let articleReact = $('.react-btn'),
        articleFeedback = $('.article-feedback');

    articleReact.on('click', function() {
        const slug = $(this).data('slug');
        const action = $(this).data('action');
        $.ajax({
            url: config.url + '/knowledgebase/articles/' + slug,
            type: 'POST',
            data: {
                action: action,
            },
            success: function(response) {
                if ($.isEmptyObject(response.error)) {
                    articleFeedback.remove();
                    toastr.success(response.success);
                } else {
                    toastr.error(response.error);
                }
            },
            error: function(error) {
                toastr.error(error);
            }
        });
    });

    let i = 1,
        attachments = $('.attachments'),
        addAttachment = $('#addAttachment');

    addAttachment.on('click', function(e) {
        e.preventDefault();
        if (i < ticketsConfig.max_file) {
            i++;
            attachments.append('<div class="attachment-box-' + i + ' mt-3">' +
                '<div class="input-group">' +
                '<input type="file" name="attachments[]" class="form-control form-control-md">' +
                '<button class="btn btn-danger attachment-remove" data-id="' + i + '" type="button">' +
                '<i class="fa-regular fa-trash-can"></i>' +
                '</button>' +
                '</div>' +
                '</div>');
        } else {
            toastr.error(ticketsConfig.max_files_error)
        }
    });

    $(document).on('click', '.attachment-remove', function() {
        let id = $(this).data("id");
        i--;
        $('.attachment-box-' + id).remove();
    });

})(jQuery);