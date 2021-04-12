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
        'root' => 1,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 20,
        'name' => 'Office equipment',
    ],
    [
        'id' => 2,
        'root' => 1,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Multifunction device',
    ],
    [
        'id' => 3,
        'root' => 1,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'Scanners',
    ],
    [
        'id' => 4,
        'root' => 1,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'Printers',
    ],
    [
        'id' => 5,
        'root' => 5,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Monitors',
    ],
    [
        'id' => 6,
        'root' => 6,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'System blocks',
    ],
    [
        'id' => 7,
        'root' => 7,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Monoblocks',
    ],
    [
        'id' => 8,
        'root' => 10,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 8,
        'name' => 'Tablets and E-Books',
    ],
    [
        'id' => 9,
        'root' => 8,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 8,
        'name' => 'Notebooks and accessories',
    ],
    [
        'id' => 10,
        'root' => 9,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 32,
        'name' => 'Server equipments',
    ],
    [
        'id' => 11,
        'root' => 3,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 14,
        'name' => 'TV and video',
    ],
    [
        'id' => 12,
        'root' => 3,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'TVs',
    ],
    [
        'id' => 13,
        'root' => 3,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'Projectors',
    ],
    [
        'id' => 14,
        'root' => 15,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 4,
        'name' => 'Conditioners',
    ],
    [
        'id' => 15,
        'root' => 16,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Video surveillance',
    ],
    [
        'id' => 16,
        'root' => 9,
        'level' => 2,
        'leftKey' => 20,
        'rightKey' => 21,
        'name' => 'Switches',
    ],
    [
        'id' => 17,
        'root' => 4,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 20,
        'name' => 'PC equipments',
    ],
    [
        'id' => 18,
        'root' => 4,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'CPUs',
    ],
    [
        'id' => 19,
        'root' => 4,
        'level' => 2,
        'leftKey' => 8,
        'rightKey' => 9,
        'name' => 'Motherboards',
    ],
    [
        'id' => 20,
        'root' => 4,
        'level' => 2,
        'leftKey' => 12,
        'rightKey' => 13,
        'name' => 'HDDs',
    ],
    [
        'id' => 21,
        'root' => 17,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Radiostations',
    ],
    [
        'id' => 22,
        'root' => 4,
        'level' => 2,
        'leftKey' => 14,
        'rightKey' => 15,
        'name' => 'Computer power bank',
    ],
    [
        'id' => 23,
        'root' => 18,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Water coolers and filters',
    ],
    [
        'id' => 24,
        'root' => 11,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 10,
        'name' => 'Shop equipments',
    ],
    [
        'id' => 25,
        'root' => 11,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Cash counters',
    ],
    [
        'id' => 26,
        'root' => 11,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'POS-devices',
    ],
    [
        'id' => 27,
        'root' => 9,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Servers',
    ],
    [
        'id' => 28,
        'root' => 2,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 14,
        'name' => 'Telephony',
    ],
    [
        'id' => 29,
        'root' => 3,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'DVD, Blu-Ray, mediaplayers',
    ],
    [
        'id' => 30,
        'root' => 10,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Tablets',
    ],
    [
        'id' => 31,
        'root' => 10,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'E-Books',
    ],
    [
        'id' => 32,
        'root' => 20,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Photo and video devices',
    ],
    [
        'id' => 33,
        'root' => 12,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 6,
        'name' => 'Mobile electronics',
    ],
    [
        'id' => 34,
        'root' => 12,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Mobile phones',
    ],
    [
        'id' => 35,
        'root' => 9,
        'level' => 2,
        'leftKey' => 22,
        'rightKey' => 23,
        'name' => 'Data sets and drivers',
    ],
    [
        'id' => 36,
        'root' => 9,
        'level' => 2,
        'leftKey' => 24,
        'rightKey' => 25,
        'name' => 'Thin-clients',
    ],
    [
        'id' => 37,
        'root' => 3,
        'level' => 2,
        'leftKey' => 8,
        'rightKey' => 9,
        'name' => 'TV set-top boxes and recivers',
    ],
    [
        'id' => 38,
        'root' => 2,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Wired phones',
    ],
    [
        'id' => 39,
        'root' => 2,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 9,
        'name' => 'PBX and system phones',
    ],
    [
        'id' => 40,
        'root' => 2,
        'level' => 2,
        'leftKey' => 10,
        'rightKey' => 11,
        'name' => 'VoIP equipments',
    ],
    [
        'id' => 41,
        'root' => 2,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'Faxs',
    ],
    [
        'id' => 42,
        'root' => 2,
        'level' => 3,
        'leftKey' => 7,
        'rightKey' => 8,
        'name' => 'System terminals',
    ],
    [
        'id' => 43,
        'root' => 14,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 10,
        'name' => 'Spare parts',
    ],
    [
        'id' => 44,
        'root' => 14,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'PCD for CBT',
    ],
    [
        'id' => 45,
        'root' => 13,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 10,
        'name' => 'Home appliances',
    ],
    [
        'id' => 46,
        'root' => 13,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Small home appliances',
    ],
    [
        'id' => 47,
        'root' => 13,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'Big home appliances',
    ],
    [
        'id' => 48,
        'root' => 13,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'Climatic',
    ],
    [
        'id' => 49,
        'root' => 12,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'Mobile phone accessories',
    ],
    [
        'id' => 50,
        'root' => 4,
        'level' => 2,
        'leftKey' => 18,
        'rightKey' => 19,
        'name' => 'PC accessories',
    ],
    [
        'id' => 51,
        'root' => 2,
        'level' => 2,
        'leftKey' => 12,
        'rightKey' => 13,
        'name' => 'Telephony accessories',
    ],
    [
        'id' => 52,
        'root' => 14,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'ICP for MBT',
    ],
    [
        'id' => 53,
        'root' => 14,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'ICP for Climatic BT',
    ],
    [
        'id' => 54,
        'root' => 11,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'ICP',
    ],
    [
        'id' => 55,
        'root' => 4,
        'level' => 2,
        'leftKey' => 10,
        'rightKey' => 11,
        'name' => 'RAM',
    ],
    [
        'id' => 56,
        'root' => 1,
        'level' => 2,
        'leftKey' => 8,
        'rightKey' => 9,
        'name' => 'Disposables',
    ],
    [
        'id' => 57,
        'root' => 4,
        'level' => 2,
        'leftKey' => 16,
        'rightKey' => 17,
        'name' => 'Routers',
    ],
    [
        'id' => 58,
        'root' => 19,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Uninterruptible power supply',
    ],
    [
        'id' => 59,
        'root' => 21,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Furniture',
    ],
    [
        'id' => 60,
        'root' => 3,
        'level' => 2,
        'leftKey' => 10,
        'rightKey' => 11,
        'name' => 'Brackets and stands',
    ],
    [
        'id' => 61,
        'root' => 1,
        'level' => 2,
        'leftKey' => 10,
        'rightKey' => 11,
        'name' => 'Stitchers',
    ],
    [
        'id' => 62,
        'root' => 1,
        'level' => 2,
        'leftKey' => 14,
        'rightKey' => 15,
        'name' => 'Printer house equipments',
    ],
    [
        'id' => 63,
        'root' => 13,
        'level' => 2,
        'leftKey' => 8,
        'rightKey' => 9,
        'name' => 'House appliance accessories',
    ],
    [
        'id' => 64,
        'root' => 1,
        'level' => 2,
        'leftKey' => 12,
        'rightKey' => 13,
        'name' => 'Shredders',
    ],
    [
        'id' => 65,
        'root' => 3,
        'level' => 2,
        'leftKey' => 12,
        'rightKey' => 13,
        'name' => 'Accessories',
    ],
    [
        'id' => 66,
        'root' => 1,
        'level' => 2,
        'leftKey' => 16,
        'rightKey' => 17,
        'name' => 'Printer accessories',
    ],
    [
        'id' => 67,
        'root' => 9,
        'level' => 2,
        'leftKey' => 26,
        'rightKey' => 27,
        'name' => 'Server accessories',
    ],
    [
        'id' => 68,
        'root' => 9,
        'level' => 3,
        'leftKey' => 11,
        'rightKey' => 12,
        'name' => 'Server HDDs (SCSI, SAS, SATA)',
    ],
    [
        'id' => 69,
        'root' => 9,
        'level' => 3,
        'leftKey' => 13,
        'rightKey' => 14,
        'name' => 'Data set HDD (FC)',
    ],
    [
        'id' => 70,
        'root' => 4,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'Cooling system',
    ],
    [
        'id' => 71,
        'root' => 9,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 19,
        'name' => 'Server components',
    ],
    [
        'id' => 72,
        'root' => 9,
        'level' => 3,
        'leftKey' => 5,
        'rightKey' => 6,
        'name' => 'CPUs',
    ],
    [
        'id' => 73,
        'root' => 9,
        'level' => 3,
        'leftKey' => 9,
        'rightKey' => 10,
        'name' => 'RAM',
    ],
    [
        'id' => 74,
        'root' => 9,
        'level' => 3,
        'leftKey' => 15,
        'rightKey' => 16,
        'name' => 'Controllers',
    ],
    [
        'id' => 75,
        'root' => 9,
        'level' => 3,
        'leftKey' => 7,
        'rightKey' => 8,
        'name' => 'Motherboards',
    ],
    [
        'id' => 76,
        'root' => 9,
        'level' => 3,
        'leftKey' => 17,
        'rightKey' => 18,
        'name' => 'Casing and spare parts',
    ],
    [
        'id' => 77,
        'root' => 15,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Accessories',
    ],
    [
        'id' => 78,
        'root' => 22,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 6,
        'name' => 'Technological equipments',
    ],
    [
        'id' => 79,
        'root' => 8,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'Accessories',
    ],
    [
        'id' => 80,
        'root' => 23,
        'level' => 1,
        'leftKey' => 1,
        'rightKey' => 2,
        'name' => 'Repair and construction',
    ],
    [
        'id' => 81,
        'root' => 4,
        'level' => 2,
        'leftKey' => 4,
        'rightKey' => 5,
        'name' => 'GPUs',
    ],
    [
        'id' => 82,
        'root' => 8,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 3,
        'name' => 'Notebooks',
    ],
    [
        'id' => 83,
        'root' => 14,
        'level' => 2,
        'leftKey' => 8,
        'rightKey' => 9,
        'name' => 'ICP for digital technology',
    ],
    [
        'id' => 84,
        'root' => 1,
        'level' => 2,
        'leftKey' => 18,
        'rightKey' => 19,
        'name' => 'Vaults',
    ],
    [
        'id' => 85,
        'root' => 10,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'Tablet accessories',
    ],
    [
        'id' => 86,
        'root' => 8,
        'level' => 2,
        'leftKey' => 6,
        'rightKey' => 7,
        'name' => 'Notebook equipments',
    ],
    [
        'id' => 87,
        'root' => 22,
        'level' => 2,
        'leftKey' => 2,
        'rightKey' => 5,
        'name' => 'Measurement devices',
    ],
    [
        'id' => 88,
        'root' => 22,
        'level' => 3,
        'leftKey' => 3,
        'rightKey' => 4,
        'name' => 'Oscilloscope',
    ],
    [
        'id' => 89,
        'root' => 11,
        'level' => 2,
        'leftKey' => 8,
        'rightKey' => 9,
        'name' => 'Cash registers',
    ],
    [
        'id' => 90,
        'root' => 9,
        'level' => 2,
        'leftKey' => 28,
        'rightKey' => 29,
        'name' => 'Concentrators',
    ],
    [
        'id' => 91,
        'root' => 9,
        'level' => 2,
        'leftKey' => 30,
        'rightKey' => 31,
        'name' => 'Network monitoring devices',
    ],
];
