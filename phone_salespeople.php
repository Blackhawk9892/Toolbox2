 
<?php
// phone_salespeople.php
// Add, remove, and reactivate salespeople.
// Requires phone_api.php in the same directory.
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>Manage Phone Salespeople</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f3f5f8;
    margin: 0;
    padding: 25px;
    color: #253246;
}

.container {
    max-width: 850px;
    margin: auto;
}

.panel {
    background: white;
    padding: 22px;
    border-radius: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
}

h1 { color: #164a78; }

input {
    padding: 12px;
    font-size: 16px;
    width: 65%;
    max-width: 400px;
    box-sizing: border-box;
}

button {
    padding: 12px 16px;
    border: none;
    border-radius: 5px;
    background: #164a78;
    color: white;
    cursor: pointer;
    font-size: 15px;
}

button:disabled {
    opacity: .5;
    cursor: wait;
}

.remove { background: #a12d2d; }
.activate { background: #237344; }

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

th { background: #edf1f5; }

#message {
    font-weight: bold;
    min-height: 24px;
}

a { color: #164a78; }
</style>
</head>

<body>
<div class="container">

<h1>Manage Salespeople</h1>

<p>
<a href="phone_rotation.php">
    Back to Phone Call Rotation
</a>
</p>

<div class="panel">
    <h2>Add Salesperson</h2>
    <p>
        New salespeople automatically go to
        the bottom of the rotation.
    </p>

    <form id="addForm">
        <input
            type="text"
            id="salespersonName"
            placeholder="Enter salesperson name"
            maxlength="120"
            required
        >
        <button type="submit">
            Add Salesperson
        </button>
    </form>
</div>

<p id="message" role="status"></p>

<div class="panel">
    <h2>Active Salespeople</h2>
    <div id="activeList">Loading...</div>
</div>

<div class="panel">
    <h2>Removed Salespeople</h2>
    <p>
        Removed salespeople keep their call history.
        You can reactivate them at any time.
    </p>
    <div id="inactiveList">Loading...</div>
</div>

</div>

<script>
"use strict";

const message = document.getElementById("message");

async function api(action, values = {}) {

    const data = new FormData();
    data.append("action", action);

    Object.entries(values).forEach(([key, value]) => {
        data.append(key, value);
    });

    const response = await fetch("phone_api.php", {
        method: "POST",
        body: data,
        credentials: "same-origin"
    });

    const result = await response.json();

    if (!response.ok || !result.success) {
        throw new Error(
            result.message || "Request failed."
        );
    }

    return result;
}

function showMessage(text, isError = false) {
    message.textContent = text;
    message.style.color =
        isError ? "#a12d2d" : "#237344";
}

function makeTable(people, active) {

    if (people.length === 0) {
        const p = document.createElement("p");
        p.textContent = active
            ? "No active salespeople."
            : "No removed salespeople.";
        return p;
    }

    const table = document.createElement("table");

    const header = document.createElement("tr");

    ["Order", "Salesperson", "Action"].forEach(text => {
        const th = document.createElement("th");
        th.textContent = text;
        header.appendChild(th);
    });

    table.appendChild(header);

    people.forEach((person, index) => {

        const row = document.createElement("tr");

        const order = document.createElement("td");
        order.textContent = active ? index + 1 : "-";

        const name = document.createElement("td");
        name.textContent = person.name;

        const action = document.createElement("td");
        const button = document.createElement("button");

        button.textContent = active
            ? "Remove"
            : "Reactivate";

        button.className = active
            ? "remove"
            : "activate";

        button.addEventListener("click", async () => {

            if (active && !confirm(
                "Remove " + person.name +
                " from the rotation?"
            )) {
                return;
            }

            button.disabled = true;

            try {
                const result = await api("set_active", {
                    id: person.id,
                    active: active ? "0" : "1"
                });

                displaySalespeople(result.salespeople);
                showMessage(result.message);

            } catch (error) {
                showMessage(error.message, true);
                button.disabled = false;
            }
        });

        action.appendChild(button);

        row.appendChild(order);
        row.appendChild(name);
        row.appendChild(action);

        table.appendChild(row);
    });

    return table;
}

function displaySalespeople(people) {

    const active = people.filter(
        p => Number(p.active) === 1
    );

    const inactive = people.filter(
        p => Number(p.active) === 0
    );

    document.getElementById("activeList")
        .replaceChildren(makeTable(active, true));

    document.getElementById("inactiveList")
        .replaceChildren(makeTable(inactive, false));
}

document.getElementById("addForm")
    .addEventListener("submit", async function(event) {

        event.preventDefault();

        const input =
            document.getElementById("salespersonName");

        const name = input.value.trim();

        if (!name) return;

        const button = this.querySelector("button");
        button.disabled = true;

        try {
            const result = await api("add", {name});

            displaySalespeople(result.salespeople);
            showMessage(result.message);

            input.value = "";

        } catch (error) {
            showMessage(error.message, true);

        } finally {
            button.disabled = false;
        }
    });

async function loadSalespeople() {
    try {
        const result = await api("list");
        displaySalespeople(result.salespeople);

    } catch (error) {
        showMessage(error.message, true);
    }
}

loadSalespeople();

</script>
</body>
</html>
