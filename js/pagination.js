  $( document ).ready(function() {
    var temp = document.getElementById("max-row");
    temp.selectedIndex = 1;

    pagination();
  });

  function paginationRemoveNumber(){
    var x = document.getElementById("max-row");
    var y = x.options[x.selectedIndex].value;

    var nb_pages = noligne / y;
    nb_pages = Math.ceil(nb_pages);

    for(var i=1; i < noligne; i++){
      if($('#button'+i).length){
        document.getElementById("button"+i).remove();
      }else{}
    }
  }
  function choixPage(min, y, val){
    min = min.replace('lignes', '');
    val = val.replace('button', '');
    var nbbtn = document.getElementById("pagination").childElementCount;

    for(var i = 0; i < noligne + 1; i++){
      $(".pagination" + i).hide();
    }

    for(var i = min - y; i < y*val; i++){
      $(".pagination" + i).show();
    }
  }



  function paginationAddNumber(){
    paginationRemoveNumber();
    var x = document.getElementById("max-row");
    var y = x.options[x.selectedIndex].value;

    var nb_pages = noligne / y;
    nb_pages = Math.ceil(nb_pages);

    for(var i=1; i < nb_pages + 1; i++){
      var z = y * i;

      var button = document.createElement("a");
      button.setAttribute("id", "button"+i);
      button.setAttribute("class", "lignes"+z);

      button.innerHTML = i;
      button.style.backgroundColor = "#e1ecfd";
      button.style.color = "black";
      button.style.padding = "10px";
      button.style.margin = "10px";

      button.onclick = function(){
        choixPage(this.getAttribute("class"), y, this.id);//button.getAttribute("class"));
      }


      var temp = document.getElementById('pagination');
      if($('#button'+i).length){

      }else{
        temp.appendChild(button);
      }
    }
  }

  function pagination(){
    var x = document.getElementById("max-row");
    var y = x.options[x.selectedIndex].value;

    paginationAddNumber();

    for(var i=0; i< noligne; i++){
      $(".pagination" + i).hide();
    }

    for(var i=0; i<y; i++){
      $(".pagination" + i).show();
    }

  }
