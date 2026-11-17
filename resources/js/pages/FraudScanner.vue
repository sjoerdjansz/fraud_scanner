<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Fraud Scanner" />
    <div class="flex h-dvh flex-col items-center bg-[#FDFDFC] text-[#1b1b18]">
        <div class="flex w-full max-w-[1024px] flex-1 flex-col gap-4 overflow-hidden p-4">
            <header class="flex w-full flex-col gap-4 rounded-md bg-gray-100 p-6 shadow-2xs">
                <div class="flex items-center justify-between gap-4">
                    <h1 class="text-2xl">Fraud Scanner</h1>
                    <button
                        class="shrink-0 cursor-pointer rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-600"
                    >
                        Get Customers
                    </button>
                </div>
                <p>This applications scans all customers and highlights the fraudulent ones.</p>
            </header>
            <main class="relative scrollbar-thumb-only flex-1 overflow-auto">
                <table
                    class="w-full border-separate border-spacing-y-2 text-left whitespace-nowrap [&_td]:bg-gray-100 [&_td]:px-3 [&_td]:py-2 [&_th]:bg-gray-800 [&_th]:px-3 [&_th]:py-2 [&_th]:text-white [&_tr>*:first-child]:rounded-l-sm [&_tr>*:last-child]:rounded-r-sm"
                >
                    <thead class="sticky top-0">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date of birth</th>
                            <th>Phone</th>
                            <th>IBAN</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        <tr>
                            <td>1</td>
                            <td>Jan de Vries</td>
                            <td>1985-03-12</td>
                            <td>+31 6 12345678</td>
                            <td>NL91ABNA0417164300</td>
                            <td>84.241.192.17</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Sanne Bakker</td>
                            <td>1992-07-28</td>
                            <td>+31 6 87654321</td>
                            <td>NL20INGB0001234567</td>
                            <td>84.241.192.17</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Lucas Jansen</td>
                            <td>2010-11-05</td>
                            <td>+31 6 23456789</td>
                            <td>NL39RABO0300065264</td>
                            <td>145.53.12.201</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Emma Visser</td>
                            <td>1978-01-19</td>
                            <td>+49 151 23456789</td>
                            <td>NL02SNSB0912345678</td>
                            <td>213.127.88.42</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Daan Smit</td>
                            <td>2001-09-30</td>
                            <td>+31 6 34567890</td>
                            <td>NL20INGB0001234567</td>
                            <td>92.109.4.156</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Fleur de Boer</td>
                            <td>1990-04-22</td>
                            <td>+31 6 45678901</td>
                            <td>NL44ABNA0581234567</td>
                            <td>77.168.23.90</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Thijs Mulder</td>
                            <td>1983-12-03</td>
                            <td>+31 6 56789012</td>
                            <td>NL61INGB0007654321</td>
                            <td>86.85.140.12</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Lotte de Groot</td>
                            <td>1996-06-14</td>
                            <td>+31 20 1234567</td>
                            <td>NL17RABO0123456789</td>
                            <td>31.151.77.205</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Bram Bos</td>
                            <td>1971-08-09</td>
                            <td>+31 6 67890123</td>
                            <td>NL86TRIO0198765432</td>
                            <td>62.45.198.33</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Julia Vos</td>
                            <td>1999-02-27</td>
                            <td>+31 6 78901234</td>
                            <td>NL55ASNB0708123456</td>
                            <td>185.12.60.74</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Ruben Peters</td>
                            <td>1988-10-16</td>
                            <td>+31 10 7654321</td>
                            <td>NL73BUNQ2045678901</td>
                            <td>94.210.31.118</td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Isa Hendriks</td>
                            <td>2004-05-08</td>
                            <td>+31 6 89012345</td>
                            <td>NL29ABNA0612345678</td>
                            <td>81.204.9.61</td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Milan van Dijk</td>
                            <td>1975-03-25</td>
                            <td>+31 6 90123456</td>
                            <td>NL08INGB0004567890</td>
                            <td>178.84.215.7</td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Noa van den Berg</td>
                            <td>1993-11-11</td>
                            <td>+31 30 2345678</td>
                            <td>NL93RABO0345678912</td>
                            <td>89.99.142.250</td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Sem Dekker</td>
                            <td>1966-07-01</td>
                            <td>+31 6 01234567</td>
                            <td>NL38SNSB0934567812</td>
                            <td>109.36.77.184</td>
                        </tr>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</template>
