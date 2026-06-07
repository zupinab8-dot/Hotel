// Dados das madeiras
const woodsData = [
    {
        id: '1',
        name: 'Pinus',
        scientificName: 'Pinus elliottii',
        description: 'Madeira leve e de fácil trabalhabilidade, ideal para construção civil e móveis econômicos.',
        characteristics: ['Leve', 'Econômica', 'Fácil de trabalhar', 'Boa absorção de acabamentos'],
        density: '450-550 kg/m³',
        pricePerM3: 850,
        image: 'https://images.unsplash.com/photo-1541292646236-d3de66311632?w=400',
        applications: ['Construção civil', 'Móveis', 'Portas', 'Janelas', 'Embalagens']
    },
    {
        id: '2',
        name: 'Eucalipto',
        scientificName: 'Eucalyptus grandis',
        description: 'Madeira versátil com boa resistência mecânica, amplamente utilizada na construção e indústria.',
        characteristics: ['Resistente', 'Versátil', 'Sustentável', 'Boa durabilidade'],
        density: '640-690 kg/m³',
        pricePerM3: 950,
        image: 'https://images.unsplash.com/photo-1768847698805-1281fbf45f1d?w=400',
        applications: ['Construção civil', 'Pisos', 'Celulose', 'Energia', 'Móveis']
    },
    {
        id: '3',
        name: 'Cedro',
        scientificName: 'Cedrela fissilis',
        description: 'Madeira nobre com aroma característico, muito apreciada para móveis finos e instrumentos musicais.',
        characteristics: ['Aromática', 'Nobre', 'Resistente a insetos', 'Fácil de trabalhar'],
        density: '480-550 kg/m³',
        pricePerM3: 2800,
        image: 'https://images.unsplash.com/photo-1638726302695-4055faf12224?w=400',
        applications: ['Móveis finos', 'Instrumentos musicais', 'Portas', 'Decoração interna']
    },
    {
        id: '4',
        name: 'Mogno',
        scientificName: 'Swietenia macrophylla',
        description: 'Madeira de luxo, extremamente valorizada por sua beleza e durabilidade excepcional.',
        characteristics: ['Luxuosa', 'Durável', 'Estável', 'Beleza incomparável'],
        density: '550-650 kg/m³',
        pricePerM3: 4500,
        image: 'https://images.unsplash.com/photo-1700906026482-f409e961aabc?w=400',
        applications: ['Móveis de luxo', 'Embarcações', 'Painéis decorativos', 'Instrumentos musicais']
    },
    {
        id: '5',
        name: 'Ipê',
        scientificName: 'Handroanthus spp',
        description: 'Uma das madeiras mais duras e resistentes do Brasil, ideal para áreas externas.',
        characteristics: ['Extremamente dura', 'Resistente à umidade', 'Durável', 'Pesada'],
        density: '960-1200 kg/m³',
        pricePerM3: 3800,
        image: 'https://images.unsplash.com/photo-1632359552106-2599db65f51c?w=400',
        applications: ['Decks', 'Assoalhos', 'Estruturas externas', 'Construção naval', 'Móveis']
    },
    {
        id: '6',
        name: 'Peroba Rosa',
        scientificName: 'Aspidosperma polyneuron',
        description: 'Madeira nobre brasileira com excelente resistência e beleza natural única.',
        characteristics: ['Resistente', 'Durável', 'Bela textura', 'Estável'],
        density: '750-850 kg/m³',
        pricePerM3: 3200,
        image: 'https://images.unsplash.com/photo-1611072337226-1140ab367200?w=400',
        applications: ['Móveis finos', 'Pisos', 'Estruturas', 'Tornearia', 'Decoração']
    },
    {
        id: '7',
        name: 'Cumaru',
        scientificName: 'Dipteryx odorata',
        description: 'Madeira de alta densidade, muito resistente e ideal para uso externo intenso.',
        characteristics: ['Muito dura', 'Resistente a cupins', 'Durável', 'Aromática'],
        density: '900-1100 kg/m³',
        pricePerM3: 3500,
        image: 'https://images.unsplash.com/photo-1642935264339-694e0fb27b0c?w=400',
        applications: ['Pisos externos', 'Decks', 'Estruturas', 'Pontes', 'Móveis']
    },
    {
        id: '8',
        name: 'Jatobá',
        scientificName: 'Hymenaea courbaril',
        description: 'Madeira brasileira resistente com coloração avermelhada, muito valorizada em pisos.',
        characteristics: ['Dura', 'Resistente', 'Bela cor', 'Durável'],
        density: '840-960 kg/m³',
        pricePerM3: 2900,
        image: 'https://images.unsplash.com/photo-1617262869595-a0e5683b8fc7?w=400',
        applications: ['Pisos', 'Móveis', 'Estruturas', 'Escadas', 'Decoração']
    },
    {
        id: '9',
        name: 'Tauari',
        scientificName: 'Couratari spp',
        description: 'Madeira clara e uniforme, ideal para móveis e acabamentos internos refinados.',
        characteristics: ['Clara', 'Uniforme', 'Estável', 'Fácil acabamento'],
        density: '620-720 kg/m³',
        pricePerM3: 1800,
        image: 'https://images.unsplash.com/photo-1747256311196-0276bcb9080b?w=400',
        applications: ['Móveis', 'Revestimentos', 'Portas', 'Compensados', 'Decoração']
    },
    {
        id: '10',
        name: 'Angelim Pedra',
        scientificName: 'Hymenolobium petraeum',
        description: 'Madeira pesada e resistente, excelente para estruturas e construção civil.',
        characteristics: ['Pesada', 'Resistente', 'Durável', 'Estável'],
        density: '880-1050 kg/m³',
        pricePerM3: 2600,
        image: 'https://images.unsplash.com/photo-1692284018462-01c64c4aa28f?w=400',
        applications: ['Estruturas', 'Construção civil', 'Pisos', 'Móveis pesados', 'Vigas']
    },
    {
        id: '11',
        name: 'Freijó',
        scientificName: 'Cordia goeldiana',
        description: 'Madeira leve com desenho marcante, muito apreciada em móveis e decoração.',
        characteristics: ['Leve', 'Desenho marcante', 'Estável', 'Fácil de trabalhar'],
        density: '560-650 kg/m³',
        pricePerM3: 2200,
        image: 'https://images.unsplash.com/photo-1457964121122-e7f8269faed3?w=400',
        applications: ['Móveis', 'Painéis decorativos', 'Portas', 'Revestimentos', 'Decoração']
    },
    {
        id: '12',
        name: 'Garapeira',
        scientificName: 'Apuleia leiocarpa',
        description: 'Madeira extremamente dura e pesada, ideal para pisos de alto tráfego.',
        characteristics: ['Extremamente dura', 'Pesada', 'Resistente', 'Durável'],
        density: '950-1150 kg/m³',
        pricePerM3: 3300,
        image: 'https://images.unsplash.com/photo-1550998212-6486417d52a6?w=400',
        applications: ['Pisos industriais', 'Estruturas pesadas', 'Tacos', 'Móveis', 'Construção']
    },
    {
        id: '13',
        name: 'Maçaranduba',
        scientificName: 'Manilkara huberi',
        description: 'Madeira muito dura e densa, resistente a intempéries e organismos xilófagos.',
        characteristics: ['Muito dura', 'Resistente à água', 'Durável', 'Pesada'],
        density: '1000-1200 kg/m³',
        pricePerM3: 3900,
        image: 'https://images.unsplash.com/photo-1682643143637-e583c5fc20a9?w=400',
        applications: ['Construção naval', 'Decks', 'Estruturas externas', 'Pontes', 'Dormentes']
    },
    {
        id: '14',
        name: 'Sucupira',
        scientificName: 'Diplotropis spp',
        description: 'Madeira pesada com excelente resistência mecânica e bela aparência.',
        characteristics: ['Pesada', 'Resistente', 'Bela textura', 'Durável'],
        density: '850-1000 kg/m³',
        pricePerM3: 3100,
        image: 'https://images.unsplash.com/photo-1628940445268-b868f942464d?w=400',
        applications: ['Pisos', 'Estruturas', 'Móveis', 'Escadas', 'Tacos']
    },
    {
        id: '15',
        name: 'Roxinho',
        scientificName: 'Peltogyne spp',
        description: 'Madeira de coloração roxa única, muito valorizada em marcenaria de luxo.',
        characteristics: ['Cor roxa única', 'Dura', 'Durável', 'Bela aparência'],
        density: '800-950 kg/m³',
        pricePerM3: 4200,
        image: 'https://images.unsplash.com/photo-1763272117701-9d4357ff0687?w=400',
        applications: ['Móveis de luxo', 'Marcenaria artística', 'Pisos especiais', 'Decoração', 'Instrumentos']
    }
];
