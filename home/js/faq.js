var prevId = null;
var prevIconId = null;
function toggleFaq(id, iconId){
  if(prevId === null){
    prevId = id;
    prevIconId = iconId;
    $(id).css({'display': 'block'});
    $(iconId).removeClass('fa-plus');
    $(iconId).addClass('fa-minus');
  }else{
    if(prevId != id){
      $(prevId).css({'display': 'none'});
      $(prevIconId).removeClass('fa-minus')
      $(prevIconId).addClass('fa-plus');
      $(id).css({'display': 'block'});
      $(iconId).removeClass('fa-plus');
      $(iconId).addClass('fa-minus')
      prevId = id;
      prevIconId = iconId;
    }else{
      $(prevId).css({'display': 'none'});
      $(prevIconId).removeClass('fa-minus');
      $(prevIconId).addClass('fa-plus');
      prevId = null;
    }
  }
}