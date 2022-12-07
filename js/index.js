function setFormValues(  ){
  fetch('./db/json/newfile.json')
    .then(res => res.json())
    .then(console.log )
  // let vars = JSON.parse(json);
  // console.log([json,vars])
}