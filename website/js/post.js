function postComment(usrId, postId) {
  let textarea = document.getElementById("comment-text-area");
  let text = textarea.value;
 
  if (text === "" || text.length > 255) {
    let modal = document.getElementById('modal');
    modal.style.display = "block";
  } else {
    axios.post(`api/api-comment.php?usrId=${usrId}&postId=${postId}&content=${text}`)
    .then(res => {
      if (res.data['ok']) {
        console.log('inserito');
        location.reload();
      } else {
        console.log('errore nel\'inserimento');
      }
    });
  }
  
}
