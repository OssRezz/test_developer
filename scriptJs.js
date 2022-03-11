getStockInformation({ dateFrom: "2000-01-05", dateTo: "2000-02-07" });

async function getStockInformation(range) {
  //Formateamos la fecha y agregamos el rango de fechas a un array
  const fechaInicio = new Date(range.dateFrom);
  const fechaFin = new Date(range.dateTo);
  const dateRange = [];
  const arregloRespuesta = [];
  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  getLongMonthName = function (date) {
    return monthNames[date.getMonth()];
  };

  while (fechaFin.getTime() >= fechaInicio.getTime()) {
    fechaInicio.setDate(fechaInicio.getDate() + 1);
    let fechaParse = fechaInicio.getDate() + "-" + (mes = getLongMonthName(new Date(fechaInicio))) + "-" + fechaInicio.getFullYear();
    dateRange.push(fechaParse);
  }

  for (let i = 0; i < dateRange.length; i++) {
    $.ajax({
      type: "GET",
      url: "http://34.194.78.120/api/test/stocks?date=" + dateRange[i],
      dataType: "json",
      success: function (result, status, xhr) {
        if (result.code != 200) {
          arregloRespuesta.push(result.data[0].date);
        }
      },
      error: function (xhr, status, error) {
        alert("Result: " + status + " " + error + " " + xhr.status + " " + xhr.statusText);
      },
    });
  }

  console.log(arregloRespuesta);
}
