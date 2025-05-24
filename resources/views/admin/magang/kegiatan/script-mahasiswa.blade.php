<script>
    const kecocokanSkill = () => {
        const titleKeahlianMahasiswa = document.querySelectorAll('.title_keahlian_mahasiswa');
        const arrtitleKeahlianMahasiswa = Array.from(titleKeahlianMahasiswa).map(title => title.textContent
            .trim());
        const titleKeahlianLowongan = document.querySelectorAll('.title_keahlian_lowongan');
        const arrtitleKeahlianLowongan = Array.from(titleKeahlianLowongan).map(title => title.textContent
            .trim());
        const matchIndexs = arrtitleKeahlianLowongan.map((title, index) => {
            return {
                title,
                index: arrtitleKeahlianMahasiswa.indexOf(title)
            }
        });

        const keahlianMahasiswa = document.querySelectorAll('.keahlian_mahasiswa');
        const arrMahasiswa = Array.from(keahlianMahasiswa, keahlian =>
            keahlian.textContent?.split('\n')
            .map(skill => skill.trim())
            .filter(skill => skill)
        );

        const keahlianLowongan = document.querySelectorAll('.keahlian_lowongan');
        const arrLowongan = Array.from(keahlianLowongan, keahlian =>
            keahlian.textContent?.split('\n')
            .map(skill => skill.trim())
            .filter(skill => skill)
        );

        const matchs = arrLowongan.map((iLowongan, index) => {
            const match = [];
            arrMahasiswa.forEach((iMahasiswa, i) => {
                if (i <= matchIndexs[index].index) {
                    iMahasiswa.forEach((skill, j) => {
                        if (iLowongan.includes(skill)) {
                            match.push({
                                skill: skill,
                                mhsIndex: j,
                                mhsIndex2: i,
                                lowonganIndex: index,
                                lowonganIndex2: iLowongan.indexOf(skill)
                            });
                        }
                    });
                }
            });
            return match;
        });

        let matchCount = 0;
        matchs.forEach((match, index) => {
            match.forEach(m => {
                keahlianLowongan[m.lowonganIndex].querySelector(
                    `span:nth-child(${m.lowonganIndex2 + 1})`).classList.add('bg-success');
                matchCount++;
            });
        });

        // console.log(matchCount);
        document.querySelector('#jumlah_keahlian_cocok').textContent = matchCount;
    };
</script>
