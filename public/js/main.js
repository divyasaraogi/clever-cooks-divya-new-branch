function showMenu() {
    let el = document.querySelector(".dropdown-content");
    if (el) {
        el.classList.toggle("dropdown-content-show");
    }
}

function showFilter(btn, cat) {
    const anchor = document.querySelector('.search > .filter');
    const selectedFilter = document.querySelector('.search > .filter > button.selected');
    if (selectedFilter == undefined 
        || selectedFilter == null 
        || selectedFilter.getAttribute('cat') !== cat) {
        btn.classList.add('selected');
        if (selectedFilter != null) {
            selectedFilter.classList.remove('selected');
        }
        let panel = document.querySelector('.search > .filter-items.expand');
        if (panel) {
            panel.classList.remove('expand');
            panel.classList.add('collapse');
        }
        panel = document.querySelector('.search > .filter-items.'+cat);
        if (panel) {
            panel.classList.remove('collapse');
            panel.classList.add('expand');
        }
    } else {
        //button is selected, so deselect and close filter items
        anchor.classList.remove('expand');
        const panel = document.querySelector('.search > .filter-items.'+cat);
        panel.classList.remove('expand');
        panel.classList.add('collapse');
        btn.classList.remove('selected');
    }
}

function changeFilter(e) {
    const bar = document.querySelector('#search-bar');
    const btn = e.currentTarget;
    const val = btn.getAttribute('val');
    const img = btn.firstElementChild;
    if (img.classList.contains('fa-times')) {
        //remove
        btn.classList.remove('selected');
        img.classList.remove('fa-times');
        img.classList.add('fa-plus');
        bar.value = bar.value.replace(new RegExp('(?<!-)\\b'+val+'\\b', 'g'), '');
        bar.value = bar.value.replace(/  +/g, ' ').trim();
    } else {
        //add
        btn.classList.add('selected');
        img.classList.remove('fa-plus');
        img.classList.add('fa-times');
        const regex = new RegExp('(?<!-)\\b'+val+'\\b', 'g');
        if (!regex.test(bar.value)) {
            if (bar.value != '') {
                bar.value += ' '
            }
            bar.value += val;
        }
    }
}

function handleKeyPress(e) {
    if (e.key === 'Enter' || e.keyCode ===13) {
        //do search
        search(e.currentTarget);
    } else {
        //update search filter
        const str = e.currentTarget.value;
        const valBtns = document.querySelectorAll('.search .filter-items button');
        for (valBtn of valBtns) {
            const val = valBtn.getAttribute('val');
            const img = valBtn.firstElementChild;
            if (str.includes(val)) {
                valBtn.classList.add('selected');
                img.classList.remove('fa-plus');
                img.classList.add('fa-times');
            } else if (valBtn.classList.contains('selected')) {
                valBtn.classList.remove('selected');
                img.classList.add('fa-plus');
                img.classList.remove('fa-times');
            }
        }
    }
}

function createCard(row) {
    const div = document.createElement('div');
    div.classList.add('card');
    if (data && data.cardList) {
        if (data.cardList.small != undefined && data.cardList.small != null) {
            div.classList.add('small');
        }
        if (data.cardList.actionUrl != '') {
            div.addEventListener('click', e => {
                window.location.href = data.cardList.actionUrl + '?id=' + row['recipe_id']; 
            });
        } else {
            div.addEventListener('click', e => {
                editRecipe(row);
            });
        }
    }
    const img = document.createElement('img');
    img.setAttribute('src', row.photo);
    div.appendChild(img);
    const span = document.createElement('span');
    span.innerText = row.name;
    div.appendChild(span);
    return div;
}

function search(bar) {
    bar.disabled = true;
    const q = encodeURIComponent(bar.value);
    ajax('GET', 'search-api.php?q='+q).then(data => {
        console.log('search', data);
        results = data;
        showResults();
        bar.disabled = false;
    }, error => {
        bar.disabled = false;
    });
}

function startSearch(img) {
    search(img.nextElementSibling);
}

//ajax
function ajax(method, url, body) {
    return new Promise( (resolve, reject) => {
        let req = new XMLHttpRequest();
        req.open(method, url);
        req.onload = () => {
            if (req.status == 200) {
                resolve(JSON.parse(req.response));
            } else {
                reject(Error(req.statusText));
            }
        };
        req.onerror = () => {
            reject(Error("Offline"));
        };
        req.send(body);
    });
}

function resetRecipeForm(form) {
    form.reset();
    const e = new Event('change');
    const ingCats = document.querySelector('#ingCats');
    ingCats.dispatchEvent(e);
}

function showResults() {
    const title = document.querySelector('.card-list h1');
    if (results != undefined && results != null && results.length > 0) {
        title.innerText = results.length  + (results.length == 1 ? ' recipe': ' recipes')  + ' found.';
        const ul = title.nextElementSibling;
        ul.innerHTML = '';
        for (let row of results) {
            ul.append(createCard(row));
        }
    } else {
        title.innerText = '0 recipe found.';
    }
}

function updateResultItem(data) {
    if (results == undefined || results == null) {
        results = [];
    }
    for (let i = 0; i < results.length; ++i) {
        if (results[i]['recipe_id'] === data['recipe_id']) {
            if (data.photo == undefined) {
                data.photo = results[i].photo;
            }
            results[i] = data;
            data = null;
            break;
        }
    }
    if (data != null) {
        results.push(data);
    }
    showResults();
}

function addRecipe() {
    if (modal.show()) {
        const form = modal.el.querySelector('form.modal-body');
        const span = form.querySelector("span");
        span.innerHTML = '';
        resetRecipeForm(form);
    }
}

function editRecipe(row) {
    if (modal.show()) {
        const form = modal.el.querySelector('form.modal-body');
        const span = form.querySelector("span");
        span.innerHTML = '';
        const deleteBtn = form.querySelector('.modal-footer button');
        const fields = form.elements;
        resetRecipeForm(form);
        ajax('GET', 'api.php?req=recing&id='+row['recipe_id']).then(data => {
            console.log('edit recipe', data);
            const ingSet = document.querySelector('#ingSet');
            for (let i of data) {
                createRecipeIngEntry(ingSet, i);
            }
            fields['name'].value = row['name'];
            fields['title'].value = row['title'];
            fields['cook-time'].value = row['cook_time'];
            fields['cal-low'].value = row['calories_low'];
            fields['cal-high'].value = row['calories_high'];
            fields['cuisine'].value = row['cuisine'];
            fields['diet'].value = row['diet'];
            fields['steps'].value = row['steps'];
            fields['recipe'].value = row['recipe_id'];
        }, error => {
            span.innerHTML = 'Something went wrong.';
            deleteBtn.classList.add('d-none');
        });
    }
}

function recSubmit(form) {
    const deleteBtn = form.querySelector('.modal-footer button');
    const inputData = new FormData(form);
    const span = form.querySelector("span");
    span.innerHTML = '';
    ajax(form.method, form.action, inputData).then(data => {
        console.log('recipe submit', data);
        if (data.error) {
            span.innerHTML = data.error;
        } else {
            updateResultItem(data);
            deleteBtn.classList.add('d-none');
            modal.hide();
        }
    }, error => {
        span.innerHTML = 'Something went wrong.';
        deleteBtn.classList.add('d-none');
    });
}

function addIng() {
    if (modal.show()) {
        const form = modal.el.querySelector('form.modal-body');
        const deleteBtn = form.querySelector('.modal-footer button');
        deleteBtn.classList.add('d-none');
        const span = form.querySelector("span");
        span.innerHTML = '';
        form.ingId.value = ''; 
        form.ing.value = '';
        form.grp.value = '';
        form.cat.value = '';
        const btn = document.createElement('button');
        const li = document.createElement('li');
        li.appendChild(btn);
        modal.state.btn = btn;
        modal.state.grp = '';
        if (!modal.state.cat) {
            modal.state.cat = form.cat.addEventListener('change', e => {
                form.grp.value = form.cat.value;
            });
        }
    }
}

function editIng(btn) {
    if (modal.show()) {
        const form = modal.el.querySelector('form.modal-body');
        const id = btn.getAttribute('id');
        form.ingId.value = id; 
        form.ing.value = btn.getAttribute('name');
        form.grp.value = btn.getAttribute('grp');
        form.cat.value = btn.getAttribute('grp');
        const deleteBtn = form.querySelector('.modal-footer button');
        deleteBtn.classList.remove('d-none');
        const span = form.querySelector("span");
        span.innerHTML = '';
        modal.state.btn = btn;
        modal.state.grp = btn.getAttribute('grp');
        modal.state.ingId = id;
        if (!modal.state.cat) {
            modal.state.cat = form.cat.addEventListener('change', e => {
                form.grp.value = form.cat.value;
            });
        }
    }
}

function deleteIng(e) {
    e.preventDefault();
    if (modal.state.ingId) {
        const form = document.querySelector('.manage .modal form.modal-body');
        const span = form.querySelector("span");
        span.innerHTML = '';
        const inputData = new FormData();
        inputData.append('ingId', modal.state.ingId);
        ajax('POST', 'api.php?req=deling', inputData).then(data => {
            console.log('delete ingredients', data);
            if (data.error) {
                span.innerHTML = data.error;
            } else {
                modal.state.btn.remove();
                modal.hide();
            }
        }, error => {
            span.innerHTML = 'Something went wrong.';
        });
    }
}

function ingSubmit(form) {
    const deleteBtn = form.querySelector('.modal-footer button');
    const inputData = new FormData(form);
    const span = form.querySelector("span");
    span.innerHTML = '';
    ajax(form.method, form.action, inputData).then(data => {
        console.log('ingredients submit', data);
        if (data.error) {
            span.innerHTML = data.error;
        } else if (data.grp === modal.state.grp) {
            //same group
            modal.state.btn.innerText = data.ing;
            modal.state.btn.setAttribute('name', data.ing);
            appendSorted(data.ing, modal.state.btn);
            modal.hide();
            deleteBtn.classList.add('d-none');
        } else {
            modal.state.btn.innerText = data.ing;
            modal.state.btn.setAttribute('name', data.ing);
            modal.state.btn.setAttribute('grp', data.grp);
            if (data.inserted) {
                modal.state.btn.setAttribute('id', data.ingId);
                const btn = modal.state.btn;
                modal.state.btn.addEventListener('click', e => {
                    editIng(btn);
                });
            }
            const grpEsc = data.grp.replaceAll(' ', '-');
            let section = document.querySelector(".manage .content."+grpEsc);
            if (section) {
                appendSorted(data.ing, modal.state.btn, section.querySelector('ul'));
            } else {
                section = document.createElement("div");
                section.classList.add('content');
                section.classList.add(grpEsc);
                const h3 = document.createElement('h3');
                h3.innerText = data.grp;
                const ul = document.createElement('ul');
                ul.classList.add('filter-items');
                ul.appendChild(modal.state.btn.parentElement);
                section.appendChild(h3);
                section.appendChild(ul);
                const main = document.querySelector('.manage');
                main.appendChild(section);
            }
            deleteBtn.classList.add('d-none');
            modal.hide();
        }
    }, error => {
        span.innerHTML = 'Something went wrong.';
        deleteBtn.classList.add('d-none');
    });
}

function appendSorted(ing, btn, ul) {
    if (!ul) {
        ul = btn.parentElement.parentElement;
    }
    const items = ul.querySelectorAll('button');
    let inserted = false;
    for (let item of items) {
        const idItem = item.getAttribute('id');
        const idBtn = btn.getAttribute('id');
        const name = item.getAttribute('name');
        if (idItem != idBtn && ing <= name) {
            ul.insertBefore(btn.parentElement, item.parentElement);
            inserted = true;
            break;
        }
    }
    if (!inserted) {
        ul.append(btn.parentElement);
    }
}

function updateSelect(data, ingSelect, ingCats) {
    const selectedCat = ingCats.value;
    if (data.filters[selectedCat]) {
        ingSelect.innerHTML = '';
        for (let item of data.filters[selectedCat]) {
            const option = document.createElement('option');
            option.innerText = capitalized(item['part_name']);
            option.setAttribute('value', item['part_id']);
            ingSelect.appendChild(option);
        }
    }
}

function createRecipeIngEntry(ingSet, item) {
    const id = item['part_id'];
    const div = document.createElement('div');
    div.classList.add('d-flex-end', 'w-100', 'mt-1');
    let el = document.createElement('input');
    el.setAttribute('type', 'hidden');
    el.setAttribute('name', 'map_'+id);
    el.value = item['map_id'] == undefined ? '': item['map_id'];   
    div.appendChild(el);
    el = document.createElement('input');
    el.classList.add('col-7');
    el.setAttribute('type', 'text');
    el.setAttribute('name', 'ing_'+id);
    el.value = item['part_name'];
    el.disabled = true;    
    div.appendChild(el);
    el = document.createElement('input');
    el.classList.add('col-2');
    el.setAttribute('type', 'text');
    el.setAttribute('name', 'qty_'+id);
    if (item['quantity'] != undefined && item['quantity'] !== '') {
        el.value = item['quantity'];
    }
    div.appendChild(el);
    el = document.createElement('select');
    el.classList.add('col-3');
    el.setAttribute('name', 'uni_'+id);
    el.innerHTML = `
        <option value="pcs">pieces</option>
        <option value="tsp">teaspoon</option>
        <option value="tbsp">tablespoon</option>
        <option value="cup">cup</option>
        <option value="ml">milliliter</option>
        <option value="ltr">liter</option>
        <option value="gl">gallon</option>
        <option value="oz">ounce</option>
        <option value="lb">pound</option>
        <option value="kg">kgs</option>
        <option value="mg">milligram</option>
        <option value="g">gram</option>
        <option value="kg">kilogram</option>`;
    if (item['unit'] == undefined) {
        el.value = 'pcs';
    } else {
        el.value = item['unit'];
    }
    div.appendChild(el);
    el = document.createElement('i');
    el.classList.add('fa', 'fa-times', 'fa-lg');
    el.addEventListener('click', e => {
        div.remove();
    });
    div.appendChild(el);
    ingSet.append(div);
    if (modal.state.removeAfter) {
        modal.state.removeAfter.push(div);
    } else {
        modal.state.removeAfter = [div];
    }
}

function addIngInline(data, ingSelect, ingCats, ingSet) {
    const selectedIng = ingSelect.value;
    if (ingSet.querySelector('input[name="map_'+selectedIng+'"]') == null) {
        const selectedCat = ingCats.value;
        for (let item of data.filters[selectedCat]) {
            const id = item['part_id'];
            if (id === selectedIng) {
                //found
                createRecipeIngEntry(ingSet, item);
                break;
            }
        }   
    }
}

function capitalized(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

window.addEventListener("click", e => {
    if (!e.target.matches('.dropbtn')) {
        let els = document.getElementsByClassName("dropdown-content");
        for (e of els) {
            if (e.classList.contains('dropdown-content-show')) {
                e.classList.remove('dropdown-content-show');
            }
        }
    }
});

let data = null; //embedded json data
let results = [];
const modal = {
    el: {},
    state: {},
    show: () => { 
        if (modal.el) { 
            modal.el.style.display = 'flex'; 
            const body = document.querySelector('body');
            body.style.overflow = 'hidden';
            return true; 
        } return false; 
    },
    hide: (e) => { 
        if (e) e.preventDefault();
        modal.el.style.display = 'none'; 
        if (modal.state.removeAfter) {
            for (let i of modal.state.removeAfter) {
                i.remove();
            }
        }
        modal.state = {}; 
        const body = document.querySelector('body');
        body.style.overflow = 'auto';
    }
};

window.addEventListener("load", () => {
    const filterBtns = document.querySelectorAll('.search > .filter > button');
    filterBtns.forEach((item) => {
        const cat = item.getAttribute('cat');
        item.addEventListener('click', (e) => {
            showFilter(item, cat);
        });
    });
    const filterItemBtns = document.querySelectorAll('.search > .filter-items > button');
    filterItemBtns.forEach((item) => {
        item.addEventListener('click', changeFilter);
    });

    const el = document.querySelector('.manage .modal');
    if (el) {
        modal.el = el;
        const form = el.querySelector('form.modal-body');
        if (form) {
            form.addEventListener('submit', e => {
                e.preventDefault();
                const name = form.getAttribute('name');
                if (name === 'ing') {
                    ingSubmit(form);
                } else if (name === 'rec') {
                    recSubmit(form);
                }
            });
        }
    }

    const bar = document.querySelector('#search-bar');
    if (bar) {
        bar.addEventListener('keyup', handleKeyPress);
    }

    const rawData = document.getElementById('_json_data');
    if (rawData != null) {
        data = JSON.parse(rawData.text);
    }
    const ingCats = document.querySelector('#ingCats');
    const ingSelect = document.querySelector('#ingSelect');
    const ingAddBtn = document.querySelector('#ingAddBtn');
    const ingSet = document.querySelector('#ingSet');
    if (data && ingCats && ingSelect && ingAddBtn && ingSet) {
        updateSelect(data, ingSelect, ingCats);
        ingCats.addEventListener('change', e => { updateSelect(data, ingSelect, e.currentTarget); });
        ingAddBtn.addEventListener('click', e => { 
            e.preventDefault();
            addIngInline(data, ingSelect, ingCats, ingSet); 
        });
    }

    const cards = document.querySelectorAll('.card-list .card');
    console.log(data, cards);
    if (data.cardList && cards && cards.length > 0) {
        for (let card of cards) {
            const id = card.getAttribute('cardid');
            if (id && id !== '') {
                card.addEventListener('click', e => {
                    window.location.href = data.cardList.actionUrl + '?id=' + id; 
                })
            }
        }
    }
});