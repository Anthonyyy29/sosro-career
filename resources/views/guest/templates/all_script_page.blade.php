{{-- FAKE LOADER SCRIPT --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const loader = document.getElementById("page-loader");

            // fade-out super smooth setelah halaman beres
            setTimeout(() => {
                loader.style.opacity = "0";
                setTimeout(() => loader.style.display = "none", 700);
            }, 400);

            // intercept semua link untuk fade-in smooth
            document.querySelectorAll("a[href]").forEach(link => {
                link.addEventListener("click", e => {
                    const url = link.getAttribute("href");

                    if (
                        !url ||
                        url.startsWith("#") ||
                        link.target === "_blank" ||
                        link.closest("form")
                    ) return;

                    e.preventDefault();

                    // show loader — smooth, no flash
                    loader.style.display = "flex";
                    setTimeout(() => loader.style.opacity = "1", 10);

                    setTimeout(() => {
                        window.location.href = url;
                    }, 100);
                });
            });
        });
    </script>

    {{-- Turnstile Js --}}
    <script>
        document.addEventListener('turnstile-error', function() {
            // Jika ada error, otomatis reset widget
            turnstile.reset();
        });
    </script>
    <script>
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('main-navbar');
            if (window.scrollY > 50) {
                // State saat di-scroll (Solid)
                nav.classList.add('bg-[#B11116]', 'shadow-lg', 'py-0');
                nav.classList.remove('py-2', 'bg-transparent');
            } else {
                // State awal (Transparent)
                nav.classList.remove('bg-[#B11116]', 'shadow-lg', 'py-0');
                nav.classList.add('py-2', 'bg-transparent');
            }
        });
    </script>

    {{-- carousel beranda --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- FITUR TRANSLATE --}}
    <div id="google_translate_element" style="display:none;"></div>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'en,id',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function changeLanguage(langCode) {
            const select = document.querySelector('#google_translate_element select');
            if (select) {
                select.value = langCode;
                select.dispatchEvent(new Event('change'));
            }
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>

    <style>
        /* Paksa sembunyikan semua UI bawaan Google yang merusak layout */
        .goog-te-banner-frame,
        .goog-te-gadget,
        .goog-te-banner,
        #goog-gt-tt,
        .goog-te-balloon-frame {
            display: none !important;
            visibility: hidden !important;
        }

        body {
            top: 0 !important;
        }

        .skiptranslate {
            display: none !important;
        }
    </style>