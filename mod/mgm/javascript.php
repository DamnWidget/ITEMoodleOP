function mgm_opciones(primaria) {
    opt1 = document.getElementById('id_opcion1');
    opt2 = document.getElementById('id_opcion2');

    if (opt1.value == opt2.value) {
        if (opt1.value == 'ninguna' || opt2.value == 'ninguna') {
            return;
        }
        if (opt2.value == 'centros') {
            if (primaria) {
                opt2.value = 'especialidades';
            } else {
                opt1.value = 'especialidades';
            }
        } else {
            if (primaria) {
                opt2.value = 'centros';
            } else {
                opt1.value = 'centros';
            }
        }
    }
}