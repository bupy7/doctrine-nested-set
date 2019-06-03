<?php declare(strict_types=1);

/**
 * Test data:
 *
 * [1] Office equipment
 * - [2] Multifunction device
 * - [4] Printers
 * - [3] Scanners
 * - [56] Disposables
 * - [61] Stitchers
 * - [64] Shredders
 * - [62] Printer house equipments
 * - [66] Printer accessories
 * - [84] Vaults
 * [28] Telephony
 * - [38] Wired phones
 * - [41] Faxs
 * - [39] PBX and system phones
 * - - [42] System terminals
 * - [40] VoIP equipments
 * - [51] Telephony accessories
 * [11] TV and video
 * - [12] TVs
 * - [13] Projectors
 * - [29] DVD, Blu-Ray, mediaplayers
 * - [37] TV set-top boxes and recivers
 * - [60] Brackets and stands
 * - [65] Accessories
 * [17] PC equipments
 * - [18] CPUs
 * - [81] GPUs
 * - [70] Cooling system
 * - [19] Motherboards
 * - [55] RAM
 * - [20] HDDs
 * - [22] Computer power bank
 * - [57] Routers
 * - [50]  PC accessories
 * [5] Monitors
 * [6] System blocks
 * [7] Monoblocks
 * [9] Notebooks and accessories
 * - [82] Notebooks
 * - [79] Accessories
 * - [86] Notebook equipments
 * [10] Server equipments
 * - [27] Servers
 * - [71] Server components
 * - - [72] CPUs
 * - - [75] Motherboards
 * - - [73] RAM
 * - - [68] Server HDDs (SCSI, SAS, SATA)
 * - - [69] Data set HDD (FC)
 * - - [74] Controllers
 * - - [76] Casing and spare parts
 * - [16] Switches
 * - [35] Data sets and drivers
 * - [36] Thin-clients
 * - [67] Server accessories
 * - [90] Concentrators
 * - [91] Network monitoring devices
 * [8] Tablets and E-Books
 * - [30] Tablets
 * - [31] E-Books
 * - [85] Tablet accessories
 * [24] Shop equipments
 * - [25] Cash counters
 * - [26] POS-devices
 * - [54] ICP
 * - [89] Cash registers
 * [33] Mobile electronics
 * - [34] Mobile phones
 * - [49] Mobile phone accessories
 * [45] Home appliances
 * - [46] Small home appliances
 * - [47] Big home appliances
 * - [48] Climatic
 * - [63] House appliance accessories
 * [43] Spare parts
 * - [44] PCD for CBT
 * - [52] ICP for MBT
 * - [53] ICP for Climatic BT
 * - [83] ICP for digital technology
 * [14] Conditioners
 * - [77] Accessories
 * [15] Video surveillance
 * [21] Radiostations
 * [23] Water coolers and filters
 * [58] Uninterruptible power supply
 * [32] Photo and video devices
 * [59] Furniture
 * [78] Technological equipments
 * - [87] Measurement devices
 * - - [88] Oscilloscope
 * [80] Repair and construction
 */

return [
    [
        'id' => 1,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 20,
        'name' => 'Office equipment',
    ],
    [
        'id' => 2,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Multifunction device',
    ],
    [
        'id' => 3,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'Scanners',
    ],
    [
        'id' => 4,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'Printers',
    ],
    [
        'id' => 5,
        'level' => 1,
        'leftKey' => 69,
        'rightKey' => 70,
        'name' => 'Monitors',
    ],
    [
        'id' => 6,
        'level' => 1,
        'leftKey' => 71,
        'rightKey' => 72,
        'name' => 'System blocks',
    ],
    [
        'id' => 7,
        'level' => 1,
        'leftKey' => 73,
        'rightKey' => 74,
        'name' => 'Monoblocks',
    ],
    [
        'id' => 8,
        'level' => 1,
        'leftKey' => 115,
        'rightKey' => 122,
        'name' => 'Tablets and E-Books',
    ],
    [
        'id' => 9,
        'level' => 1,
        'leftKey' => 75,
        'rightKey' => 82,
        'name' => 'Notebooks and accessories',
    ],
    [
        'id' => 10,
        'level' => 1,
        'leftKey' => 83,
        'rightKey' => 114,
        'name' => 'Server equipments',
    ],
    [
        'id' => 11,
        'level' => 1,
        'leftKey' => 35,
        'rightKey' => 48,
        'name' => 'TV and video',
    ],
    [
        'id' => 12,
        'level' => 2,
        'leftKey' => 36,
        'rightKey' => 37,
        'name' => 'TVs',
    ],
    [
        'id' => 13,
        'level' => 2,
        'leftKey' => 38,
        'rightKey' => 39,
        'name' => 'Projectors',
    ],
    [
        'id' => 14,
        'level' => 1,
        'leftKey' => 159,
        'rightKey' => 162,
        'name' => 'Conditioners',
    ],
    [
        'id' => 15,
        'level' => 1,
        'leftKey' => 163,
        'rightKey' => 164,
        'name' => 'Video surveillance',
    ],
    [
        'id' => 16,
        'level' => 2,
        'leftKey' => 102,
        'rightKey' => 103,
        'name' => 'Switches',
    ],
    [
        'id' => 17,
        'level' => 1,
        'leftKey' => 49,
        'rightKey' => 68,
        'name' => 'PC equipments',
    ],
    [
        'id' => 18,
        'level' => 2,
        'leftKey' => 50,
        'rightKey' => 51,
        'name' => 'CPUs',
    ],
    [
        'id' => 19,
        'level' => 2,
        'leftKey' => 56,
        'rightKey' => 57,
        'name' => 'Motherboards',
    ],
    [
        'id' => 20,
        'level' => 2,
        'leftKey' => 60,
        'rightKey' => 61,
        'name' => 'HDDs',
    ],
    [
        'id' => 21,
        'level' => 1,
        'leftKey' => 165,
        'rightKey' => 166,
        'name' => 'Radiostations',
    ],
    [
        'id' => 22,
        'level' => 2,
        'leftKey' => 62,
        'rightKey' => 63,
        'name' => 'Computer power bank',
    ],
    [
        'id' => 23,
        'level' => 1,
        'leftKey' => 167,
        'rightKey' => 168,
        'name' => 'Water coolers and filters',
    ],
    [
        'id' => 24,
        'level' => 1,
        'leftKey' => 123,
        'rightKey' => 132,
        'name' => 'Shop equipments',
    ],
    [
        'id' => 25,
        'level' => 2,
        'leftKey' => 124,
        'rightKey' => 125,
        'name' => 'Cash counters',
    ],
    [
        'id' => 26,
        'level' => 2,
        'leftKey' => 126,
        'rightKey' => 127,
        'name' => 'POS-devices',
    ],
    [
        'id' => 27,
        'level' => 2,
        'leftKey' => 84,
        'rightKey' => 85,
        'name' => 'Servers',
    ],
    [
        'id' => 28,
        'level' => 1,
        'leftKey' => 21,
        'rightKey' => 34,
        'name' => 'Telephony',
    ],
    [
        'id' => 29,
        'level' => 2,
        'leftKey' => 40,
        'rightKey' => 41,
        'name' => 'DVD, Blu-Ray, mediaplayers',
    ],
    [
        'id' => 30,
        'level' => 2,
        'leftKey' => 116,
        'rightKey' => 117,
        'name' => 'Tablets',
    ],
    [
        'id' => 31,
        'level' => 2,
        'leftKey' => 118,
        'rightKey' => 119,
        'name' => 'E-Books',
    ],
    [
        'id' => 32,
        'level' => 1,
        'leftKey' => 171,
        'rightKey' => 172,
        'name' => 'Photo and video devices',
    ],
    [
        'id' => 33,
        'level' => 1,
        'leftKey' => 133,
        'rightKey' => 138,
        'name' => 'Mobile electronics',
    ],
    [
        'id' => 34,
        'level' => 2,
        'leftKey' => 134,
        'rightKey' => 135,
        'name' => 'Mobile phones',
    ],
    [
        'id' => 35,
        'level' => 2,
        'leftKey' => 104,
        'rightKey' => 105,
        'name' => 'Data sets and drivers',
    ],
    [
        'id' => 36,
        'level' => 2,
        'leftKey' => 106,
        'rightKey' => 107,
        'name' => 'Thin-clients',
    ],
    [
        'id' => 37,
        'level' => 2,
        'leftKey' => 42,
        'rightKey' => 43,
        'name' => 'TV set-top boxes and recivers',
    ],
    [
        'id' => 38,
        'level' => 2,
        'leftKey' => 22,
        'rightKey' => 23,
        'name' => 'Wired phones',
    ],
    [
        'id' => 39,
        'level' => 2,
        'leftKey' => 26,
        'rightKey' => 29,
        'name' => 'PBX and system phones',
    ],
    [
        'id' => 40,
        'level' => 2,
        'leftKey' => 30,
        'rightKey' => 31,
        'name' => 'VoIP equipments',
    ],
    [
        'id' => 41,
        'level' => 2,
        'leftKey' => 24,
        'rightKey' => 25,
        'name' => 'Faxs',
    ],
    [
        'id' => 42,
        'level' => 3,
        'leftKey' => 27,
        'rightKey' => 28,
        'name' => 'System terminals',
    ],
    [
        'id' => 43,
        'level' => 1,
        'leftKey' => 149,
        'rightKey' => 158,
        'name' => 'Spare parts',
    ],
    [
        'id' => 44,
        'level' => 2,
        'leftKey' => 150,
        'rightKey' => 151,
        'name' => 'PCD for CBT',
    ],
    [
        'id' => 45,
        'level' => 1,
        'leftKey' => 139,
        'rightKey' => 148,
        'name' => 'Home appliances',
    ],
    [
        'id' => 46,
        'level' => 2,
        'leftKey' => 140,
        'rightKey' => 141,
        'name' => 'Small home appliances',
    ],
    [
        'id' => 47,
        'level' => 2,
        'leftKey' => 142,
        'rightKey' => 143,
        'name' => 'Big home appliances',
    ],
    [
        'id' => 48,
        'level' => 2,
        'leftKey' => 144,
        'rightKey' => 145,
        'name' => 'Climatic',
    ],
    [
        'id' => 49,
        'level' => 2,
        'leftKey' => 136,
        'rightKey' => 137,
        'name' => 'Mobile phone accessories',
    ],
    [
        'id' => 50,
        'level' => 2,
        'leftKey' => 66,
        'rightKey' => 67,
        'name' => 'PC accessories',
    ],
    [
        'id' => 51,
        'level' => 2,
        'leftKey' => 32,
        'rightKey' => 33,
        'name' => 'Telephony accessories',
    ],
    [
        'id' => 52,
        'level' => 2,
        'leftKey' => 152,
        'rightKey' => 153,
        'name' => 'ICP for MBT',
    ],
    [
        'id' => 53,
        'level' => 2,
        'leftKey' => 154,
        'rightKey' => 155,
        'name' => 'ICP for Climatic BT',
    ],
    [
        'id' => 54,
        'level' => 2,
        'leftKey' => 128,
        'rightKey' => 129,
        'name' => 'ICP',
    ],
    [
        'id' => 55,
        'level' => 2,
        'leftKey' => 58,
        'rightKey' => 59,
        'name' => 'RAM',
    ],
    [
        'id' => 56,
        'level' => 2,
        'leftKey' => 8,
        'rightKey' => 9,
        'name' => 'Disposables',
    ],
    [
        'id' => 57,
        'level' => 2,
        'leftKey' => 64,
        'rightKey' => 65,
        'name' => 'Routers',
    ],
    [
        'id' => 58,
        'level' => 1,
        'leftKey' => 169,
        'rightKey' => 170,
        'name' => 'Uninterruptible power supply',
    ],
    [
        'id' => 59,
        'level' => 1,
        'leftKey' => 173,
        'rightKey' => 174,
        'name' => 'Furniture',
    ],
    [
        'id' => 60,
        'level' => 2,
        'leftKey' => 44,
        'rightKey' => 45,
        'name' => 'Brackets and stands',
    ],
    [
        'id' => 61,
        'level' => 2,
        'leftKey' => 10,
        'rightKey' => 11,
        'name' => 'Stitchers',
    ],
    [
        'id' => 62,
        'level' => 2,
        'leftKey' => 14,
        'rightKey' => 15,
        'name' => 'Printer house equipments',
    ],
    [
        'id' => 63,
        'level' => 2,
        'leftKey' => 146,
        'rightKey' => 147,
        'name' => 'House appliance accessories',
    ],
    [
        'id' => 64,
        'level' => 2,
        'leftKey' => 12,
        'rightKey' => 13,
        'name' => 'Shredders',
    ],
    [
        'id' => 65,
        'level' => 2,
        'leftKey' => 46,
        'rightKey' => 47,
        'name' => 'Accessories',
    ],
    [
        'id' => 66,
        'level' => 2,
        'leftKey' => 16,
        'rightKey' => 17,
        'name' => 'Printer accessories',
    ],
    [
        'id' => 67,
        'level' => 2,
        'leftKey' => 108,
        'rightKey' => 109,
        'name' => 'Server accessories',
    ],
    [
        'id' => 68,
        'level' => 3,
        'leftKey' => 93,
        'rightKey' => 94,
        'name' => 'Server HDDs (SCSI, SAS, SATA)',
    ],
    [
        'id' => 69,
        'level' => 3,
        'leftKey' => 95,
        'rightKey' => 96,
        'name' => 'Data set HDD (FC)',
    ],
    [
        'id' => 70,
        'level' => 2,
        'leftKey' => 54,
        'rightKey' => 55,
        'name' => 'Cooling system',
    ],
    [
        'id' => 71,
        'level' => 2,
        'leftKey' => 86,
        'rightKey' => 101,
        'name' => 'Server components',
    ],
    [
        'id' => 72,
        'level' => 3,
        'leftKey' => 87,
        'rightKey' => 88,
        'name' => 'CPUs',
    ],
    [
        'id' => 73,
        'level' => 3,
        'leftKey' => 91,
        'rightKey' => 92,
        'name' => 'RAM',
    ],
    [
        'id' => 74,
        'level' => 3,
        'leftKey' => 97,
        'rightKey' => 98,
        'name' => 'Controllers',
    ],
    [
        'id' => 75,
        'level' => 3,
        'leftKey' => 89,
        'rightKey' => 90,
        'name' => 'Motherboards',
    ],
    [
        'id' => 76,
        'level' => 3,
        'leftKey' => 99,
        'rightKey' => 100,
        'name' => 'Casing and spare parts',
    ],
    [
        'id' => 77,
        'level' => 2,
        'leftKey' => 160,
        'rightKey' => 161,
        'name' => 'Accessories',
    ],
    [
        'id' => 78,
        'level' => 1,
        'leftKey' => 175,
        'rightKey' => 180,
        'name' => 'Technological equipments',
    ],
    [
        'id' => 79,
        'level' => 2,
        'leftKey' => 78,
        'rightKey' => 79,
        'name' => 'Accessories',
    ],
    [
        'id' => 80,
        'level' => 1,
        'leftKey' => 181,
        'rightKey' => 182,
        'name' => 'Repair and construction',
    ],
    [
        'id' => 81,
        'level' => 2,
        'leftKey' => 52,
        'rightKey' => 53,
        'name' => 'GPUs',
    ],
    [
        'id' => 82,
        'level' => 2,
        'leftKey' => 76,
        'rightKey' => 77,
        'name' => 'Notebooks',
    ],
    [
        'id' => 83,
        'level' => 2,
        'leftKey' => 156,
        'rightKey' => 157,
        'name' => 'ICP for digital technology',
    ],
    [
        'id' => 84,
        'level' => 2,
        'leftKey' => 18,
        'rightKey' => 19,
        'name' => 'Vaults',
    ],
    [
        'id' => 85,
        'level' => 2,
        'leftKey' => 120,
        'rightKey' => 121,
        'name' => 'Tablet accessories',
    ],
    [
        'id' => 86,
        'level' => 2,
        'leftKey' => 80,
        'rightKey' => 81,
        'name' => 'Notebook equipments',
    ],
    [
        'id' => 87,
        'level' => 2,
        'leftKey' => 176,
        'rightKey' => 179,
        'name' => 'Measurement devices',
    ],
    [
        'id' => 88,
        'level' => 3,
        'leftKey' => 177,
        'rightKey' => 178,
        'name' => 'Oscilloscope',
    ],
    [
        'id' => 89,
        'level' => 2,
        'leftKey' => 130,
        'rightKey' => 131,
        'name' => 'Cash registers',
    ],
    [
        'id' => 90,
        'level' => 2,
        'leftKey' => 110,
        'rightKey' => 111,
        'name' => 'Concentrators',
    ],
    [
        'id' => 91,
        'level' => 2,
        'leftKey' => 112,
        'rightKey' => 113,
        'name' => 'Network monitoring devices',
    ],
];
