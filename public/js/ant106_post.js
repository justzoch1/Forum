/*! version : 1.0.0 */

function toggleReplyForm(event, formId) {
    event.preventDefault();
    const form = document.getElementById(formId);
    if (form.style.display === "none") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}
