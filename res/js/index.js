var validImg = false;
const classEnable = 'btn btn-primary mx-2'
const classDisable = 'btn btn-primary mx-2 disabled'
const urlDb = 'res/json/db.json'
const urlImg = 'res/img/'
const urlAud = 'res/audio/'

document.addEventListener("DOMContentLoaded", e => {
  let time = new Date().getHours();
  let radio = document.getElementById("radio");
  let player = document.getElementById("radionocturna");
  let radiom = document.getElementById("radiom");
  let playerm = document.getElementById("radionocturnam");
  if( time > 4 && time < 21 ){
    radio.className = "d-block";
    radiom.className = "d-block";
    player.className = "d-none";
    playerm.className = "d-none";
  }
  else {
    player.className = "d-block";
    playerm.className = "d-block";
    radio.className = "d-none";
    radiom.className = "d-none";
  }
})

/**
 * Llenado del formulario para la edicion de la publicacion
 * @param {string} id Id de la publicacion
 */
function setFormValues( id ){
  fetch(urlDb)
    .then(res => res.json())
    .then(a => {
      let info = JSON.parse(a[id]);
      console.log(info)
      document.getElementById("pubTitleEdit").value = info.titulo
      document.getElementById("resumenEdit").value = info.resumen
      document.getElementById("contenidoEdit").value = info.contenido
      document.getElementById("autorEdit").value = info.autor
      document.getElementById("ytURLEdit").value = info.ytURL
      document.getElementById("pubEdit").value = info.id
      if(info.imagen[0]!= '**No Imagen**') document.getElementById("previewImg1Edit").src = urlImg+info.imagen[0]
      if(info.imagen[1]!= '**No Imagen**') document.getElementById("previewImg2Edit").src = urlImg+info.imagen[1]
      let pv = document.getElementById("previewAudioEdit")
      info.audios.forEach(e => {
        let div = document.createElement("div");
        let audio = document.createElement("audio");
        let p = document.createElement("p");
        let btn = document.createElement("button");
    
        btn.type = "button";
        btn.className = "btn-close";
        btn.onclick = () => pv.removeChild(div);
    
        div.className = "container d-flex justify-content-around align-items-center";
    
        if(e == "**No Audio**"){
          p.textContent = "**No Audio**"
        }
        else{
          p.textContent + e
          audio.preload = "auto";
          audio.src = urlAud + e
          audio.controls = true;
        }
    
        div.appendChild(p);
        div.appendChild(audio);
        div.appendChild(btn);
        pv.appendChild(div);
      });
      /**
       * 
  let pv = document.getElementById("previewAudio"+id)
  clearPreviewAudio(id)
  for(let i = 0; i < files.length; i++) {
    let el = e.target.files[i];
    let div = document.createElement("div");
    let audio = document.createElement("audio");
    let p = document.createElement("p");
    let btn = document.createElement("button");

    btn.type = "button";
    btn.className = "btn-close";
    btn.onclick = () => pv.removeChild(div);

    p.textContent = (i+1).toString()+'- '+el.name;
    div.className = "container d-flex justify-content-around align-items-center";

    audio.preload = "auto";
    audio.src = URL.createObjectURL(el)
    audio.controls = true;

    div.appendChild(p);
    div.appendChild(audio);
    div.appendChild(btn);
    pv.appendChild(div);
  }
       */
    })
}

function previewImg(e, previewDiv){
  if(e.target.value != "")
    validImg = true;
  let imgTmp = e.target.files[0];
  let pv = document.getElementById(previewDiv)
  pv.src = URL.createObjectURL(imgTmp)
  // pv.onload = () => {
  //   URL.revokeObjectURL(pv.src)
  // }
  // console.log([imgTmp,pv])
}

function validateSubmitTxt(id){
  console.log("exec")
  console.log(validImg)
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


/**
 * Limpia el div que contiene los preview de Audio
 * @param {string} id Id de tipo de formulario modal (New/Edit)
 */
function clearPreviewAudio(id){
  let div = document.getElementById("previewAudio"+id)
  for(let i = 0; i < div.children.length; i++)
    div.removeChild(div.children[0])
}

/**
 * Limpia el formulario, preparandolo para otras publicaciones
 * @param {string} id Id de tipo de formulario modal (New/Edit)
 */
function clearForm(id){
  document.getElementById("pubTitle"+id).value = ''
  document.getElementById("resumen"+id).value = ''
  document.getElementById("contenido"+id).value = ''
  document.getElementById("autor"+id).value = ''
  document.getElementById("ytURL"+id).value = ''
  document.getElementById(id).className = classDisable
  document.getElementById("image1"+id).value=''
  document.getElementById("previewImg1"+id).src = ''
  document.getElementById("image2"+id).value=''
  document.getElementById("previewImg2"+id).src = ''
  document.getElementById("audio"+id).value = ''
  clearPreviewAudio(id)
}

/**
 * Genera los distintos audios en funcion de los audios seleccionados
 * @param {Event} e Evento que manda a ejecutar la funcion
 * @param {string} id Id de tipo de formulario modal (New/Edit)
 */
function previewAudio(e,id){
  let pv = document.getElementById("previewAudio"+id)
  clearPreviewAudio(id)
  for(let i = 0; i < e.target.files.length; i++) {
    let el = e.target.files[i];
    let div = document.createElement("div");
    let audio = document.createElement("audio");
    let p = document.createElement("p");
    let btn = document.createElement("button");

    btn.type = "button";
    btn.className = "btn-close";
    btn.onclick = () => pv.removeChild(div);

    p.textContent = (i+1).toString()+'- '+el.name;
    div.className = "container d-flex justify-content-around align-items-center";

    audio.preload = "auto";
    audio.src = URL.createObjectURL(el)
    audio.controls = true;

    div.appendChild(p);
    div.appendChild(audio);
    div.appendChild(btn);
    pv.appendChild(div);
  }

}

