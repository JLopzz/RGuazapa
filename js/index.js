var validImg = false;

document.addEventListener("DOMContentLoaded", e => {
  let time = new Date().getHours();
  let radio = document.getElementById("radio");
  let player = document.getElementById("rbcloud_player19724");
  if( time > 4 && time < 21 ){
    radio.className = "d-block";
    player.className = "d-none";
  }
  else {
    player.className = "d-block";
    radio.className = "d-none";
  }
})

function setFormValues( id ){
  fetch('./db/json/test.json')
  // fetch('./db/json/newfile.json')
    .then(res => res.json())
    .then(a => {
      let info = JSON.parse(a[id]);
      document.getElementById("pubTitleEdit").value = info.titulo
      if(info.imagen[0]!= '**No Imagen**') document.getElementById("previewImg1Edit").src = 'db/img/'+info.imagen[0]
      if(info.imagen[1]!= '**No Imagen**') document.getElementById("previewImg2Edit").src = 'db/img/'+info.imagen[1]
      document.getElementById("resumenEdit").value = info.resumen
      document.getElementById("contenidoEdit").value = info.contenido
      document.getElementById("autorEdit").value = info.autor
      document.getElementById("ytURLEdit").value = info.ytURL
      document.getElementById("pubEdit").value = info.id
    })
}

function previewImg(e, preview){
  if(e.target.value != "")
    validImg = true;
  let imgTmp = e.target.files[0];
  let pv = document.getElementById(preview)
  pv.src = URL.createObjectURL(imgTmp)
  // pv.onload = () => {
  //   URL.revokeObjectURL(pv.src)
  // }
  // console.log([imgTmp,pv])
}

function validateSubmitTxt(id){
  classEnable = 'btn btn-primary mx-2'
  classDisable = 'btn btn-primary mx-2 disabled'
  console.log("exec")
  if(id == "Edit") validImg = true;
  // btnSubmitEl = document.getElementById(id)
    if(
      /*pubTitle = */document.getElementById("pubTitle"+id).value != "" &&
      /*resumen = */document.getElementById("resumen"+id).value != "" &&
      /*contenido = */document.getElementById("contenido"+id).value != "" &&
      /*autor = */document.getElementById("autor"+id).value != "" &&
      validImg
      // /*previewImg1 = */document.getElementById("image1"+id).value != undefined )
      // /*previewImg2 = */document.getElementById("previewImg2"+id).value == undefined
    ){
        document.getElementById(id).className = classEnable
    }
    else
      document.getElementById(id).className = classDisable
}

function disableSubmit(id){
  document.getElementById(id).className = 'btn btn-primary mx-2 disabled'
}
