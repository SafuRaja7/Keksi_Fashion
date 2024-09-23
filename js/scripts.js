window.onload = function () {
  fetchProducts();
};

function fetchProducts() {
  fetch("php/fetch_products.php")
    .then((response) => response.json())
    .then((products) => {
      const productsDiv = document.getElementById("products");
      products.forEach((product) => {
        productsDiv.innerHTML += `
                    <div class="card">
                        <h3>${product.name}</h3>
                        <p>${product.description}</p>
                        <p>Price: $${product.price}</p>
                        <button onclick="placeOrder(${product.product_id})">Buy</button>
                    </div>
                `;
      });
    });
}

function placeOrder(productId) {
  const formData = new FormData();
  formData.append("product_id", productId);
  formData.append("quantity", 1);

  fetch("php/place_order.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((result) => alert(result));
}
