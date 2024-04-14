async function doHypermediaCall(elem, url) {
  const response = await fetch(url);
  if (response.ok) {
    elem.innerHTML = await response.text();
    for (const anchor of elem.querySelectorAll("a")) {
      anchor.addEventListener("click", async (e) => {
        e.preventDefault();
        await doHypermediaCall(elem, anchor.getAttribute("href"));
      });
    }
  }
}

window.addEventListener("load", async () => {
  const elem = document.body.querySelector("[data-load-href]");
  await doHypermediaCall(elem, elem.getAttribute("data-load-href"));
});
