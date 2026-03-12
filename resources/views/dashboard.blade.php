<x-app-layout>
    <div class="page-header">
        <div class="breadcrumb">
            <h3>Dashboard</h3>
            <span>Home</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: #888ea8;"></i>
            <span style="color: #888ea8;">Overview</span>
        </div>
        <div class="action-buttons">
            <button class="btn-icon"><i class="fa-solid fa-chart-column"></i></button>
            <button class="btn-icon"><i class="fa-solid fa-filter"></i></button>
            <button class="btn-icon"><i class="fa-solid fa-paperclip"></i></button>
            <button class="btn-primary"><i class="fa-solid fa-plus"></i> New</button>
        </div>
    </div>

    <div class="card">
        <div class="table-controls">
            <div class="show-entries">
                Show
                <select>
                    <option>10</option>
                </select>
                entries
            </div>
            <div class="search-box">
                <label>Search:</label>
                <input type="text">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;"><div class="checkbox-custom"></div></th>
                    <th style="width: 50px;"><i class="fa-solid fa-sort"></i></th>
                    <th>ITEM <i class="fa-solid fa-sort"></i></th>
                    <th>DETAIL <i class="fa-solid fa-sort"></i></th>
                    <th>STATUS <i class="fa-solid fa-sort"></i></th>
                    <th>DATE <i class="fa-solid fa-sort"></i></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><div class="checkbox-custom"></div></td>
                    <td></td>
                    <td class="id-text">#GEN-001</td>
                    <td class="subject-text">Welcome to the dashboard</td>
                    <td class="amount-text">Active</td>
                    <td>202...</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>
