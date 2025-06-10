<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('mediaQueryChange', (event) => {
            const result = event.detail;
            const info1 = document.querySelector('.perusahaan_info_1');
            const info2 = document.querySelector('.perusahaan_info_2');
            if (!info1 || !info2) return;
            switch (result) {
                case 'xs':
                case 'sm':
                case 'md':
                    info1.classList.add('d-none');
                    info2.classList.remove('d-none');
                    break;
                default:
                    info1.classList.remove('d-none');
                    info2.classList.add('d-none');
                    break;
            }
        });
    });

    const initLowongan = () => runMediaQuery();
</script>
