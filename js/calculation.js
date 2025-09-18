// ВЫЧИСЛЕНИЕ МАССЫ ПЛЁНКИ ПО ДЛИНЕ, ШИРИНЕ, УДЕЛЬНОМУ ВЕСУ
// length - длина
// width - ширина
// density - удельный вес
function GetFilmWeightByLengthWidth(length, width, density) {
    var weight = (length * density * width) / 1000 / 1000;
    return weight;
}

// ВЫЧИСЛЕНИЕ ДЛИНЫ И МАССЫ ПЛЁНКИ ПО ШПУЛЕ, ТОЛЩИНЕ, РАДИУСУ, ШИРИНЕ, УДЕЛЬНОМУ ВЕСУ
// spool - шпуля
// thickness - толщина
// radius - радиус от вала
// width - ширина
// density - удельный вес
function GetFilmLengthWeightBySpoolThicknessRadiusWidth(spool, thickness, radius, width, density) {
    var length = null;
    var weight = null;
    
    if(spool == 76) {
        length = (0.15 * radius * radius + 11.3961 * radius - 176.4427) * 20 / thickness;
        weight = GetFilmWeightByLengthWidth(length, width, density);
    }
    else if(spool == 152) {
        length = (0.1524 * radius * radius + 23.1245 * radius - 228.5017) * 20 / thickness;
        weight = GetFilmWeightByLengthWidth(length, width, density);
    }
    
    return { length: length, weight: weight };
}