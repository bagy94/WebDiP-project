<section class="content">
    <section class="content">
        <div class="box search-box">
            <label for="selectColumn">
                Pretraži:
            </label>
            <select id="selectColumn">
                <option id="-1">Stupac</option>
                <option id="1">Akcija</option>
                <option id="2">Kategorija</option>
                <option id="3">Korisnik</option>
                <option id="4">Vrijeme</option>
            </select>
            <input type="text" id="inputSrch" name="q" placeholder="Pretraži" disabled>
        </div>
        <table id="tableLog" class="table" data-page="1">
            <thead>
            <tr>
                <th class="small-cell">
                    #
                </th>
                <th>
                    Akcija
                </th>
                <th>
                    Kategorija
                </th>
                <th class="small-cell">
                    Korisnik
                </th>
                <th class="medium-cell">
                    Sadržaj
                </th>
                <th>
                    Vrijeme
                </th>

            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div class="box buttons-box">
            <input type="image" src="../view/asset/ic_previous_page.png" alt="Prva stranica" value="F" name="navbutton">
            <input type="image" src="../view/asset/ic_previous_page.png" alt="Prethodna stranica" value="P" name="navbutton">
            <input type="image" src="../view/asset/ic_next_page.png" alt="Iduća stranica" value="N" name="navbutton">
            <input type="image" src="../view/asset/ic_last_page.png" alt="Zadnja stranica" value="L" name="navbutton">
        </div>

    </section>
</section>