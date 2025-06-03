<script>
    const initLowongan = () => {
        const runMediaQuery = () => {
            const mediaQueryPerusahaanInfo = (result) => {          
                const info1 = document.querySelector('.perusahaan_info_1');
                const info2 = document.querySelector('.perusahaan_info_2');
                if (!info1 || !info2) return;
                switch (result) {
                    case 'xs':
                    case 'sm':
                    case 'md':
                    case 'lg':
                    case 'xl':
                        info1.classList.add('d-none');
                        info2.classList.remove('d-none');
                        break;
                    default:
                        info1.classList.remove('d-none');
                        info2.classList.add('d-none');
                        break;
                }
            };
            const existingIndex = useMediaQuery.arr.findIndex(fn =>
                fn.toString() === mediaQueryPerusahaanInfo.toString()
            );
            if (existingIndex !== -1) {
                useMediaQuery.arr.splice(existingIndex, 1);
            }
            useMediaQuery.arr.push(mediaQueryPerusahaanInfo);
            useMediaQuery.change();
        };
        runMediaQuery();
    };
</script>
