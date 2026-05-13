/* VIBEZ — App root: state, filters, mood logic */
const { useState, useMemo, useEffect } = React;

function App() {
  const [tweaks, setTweak] = useTweaks(window.TWEAK_DEFAULTS);
  const [category, setCategory] = useState('Todo');
  const [mood, setMood] = useState(null);
  const [open, setOpen] = useState(null);
  const [toast, setToast] = useState(null);

  // Apply aesthetic tweaks to body
  useEffect(() => {
    document.body.dataset.aesthetic = tweaks.aesthetic;
    document.body.dataset.hero = tweaks.heroLayout;
  }, [tweaks.aesthetic, tweaks.heroLayout]);

  const featured = window.EVENTOS_DATA.find(e => e.featured) || window.EVENTOS_DATA[0];
  const otros = window.EVENTOS_DATA.filter(e => !e.featured);

  // Mock de usuario logueado
  const user = {
    name: 'Samuel',
    firstName: 'Sam',
    initials: 'SM',
    tickets: 3,
    following: 12,
    points: 1840,
    coupons: 2
  };
  const misTickets = [
    { id: 'VBZ-4821', cantidad: 2, evento: window.EVENTOS_DATA[0] },
    { id: 'VBZ-4822', cantidad: 1, evento: window.EVENTOS_DATA[1] },
    { id: 'VBZ-4823', cantidad: 1, evento: window.EVENTOS_DATA[3] }
  ];

  // Filter logic
  const moodMap = {
    rave: ['Techno', 'Bass'],
    indie: ['Concierto', 'Festival'],
    perreo: ['Reggaeton'],
    chill: ['Jazz'],
    discoteca: ['Disco', 'Remember'],
    concierto: ['Concierto', 'Festival']
  };

  const filtered = useMemo(() => {
    let list = otros;
    if (category !== 'Todo') list = list.filter(e => e.categoria === category);
    if (mood && moodMap[mood]) list = list.filter(e => moodMap[mood].includes(e.categoria));
    return list;
  }, [category, mood]);

  const handleBuy = (e) => {
    setToast(`✓ ${e.titulo} guardado en tu carrito`);
    setOpen(null);
    setTimeout(() => setToast(null), 2400);
  };

  const showToast = (msg) => {
    setToast(msg);
    setTimeout(() => setToast(null), 2400);
  };

  const marqueeItems = [
    'Esta noche se rompe',
    'Charlotte de Witte · 09 May',
    'Solo quedan 87 entradas',
    'Primavera Sound · phase 3',
    'No te lo pierdas',
    'Pacha · Bad Bunny · 21 Jun',
    'BCN never sleeps',
    'Lista negra · membership'
  ];

  const heroEl = tweaks.loggedIn
    ? <LoggedHero user={user} onOpen={(e) => setOpen(e)} eventos={[featured, ...otros].slice(0, 3)} />
    : <HeroPoster evento={featured} onOpen={(e) => setOpen(e)} />;

  return (
    <>
      <VibezNav user={tweaks.loggedIn ? user : null} onLogin={(it) => showToast(it ? `→ ${it} (mock)` : '→ Login (mock)')} />
      {heroEl}
      {tweaks.loggedIn && <MisTickets tickets={misTickets} onOpen={(e) => setOpen(e)} />}
      {tweaks.loggedIn && <ParaTi user={user} eventos={otros.slice(0, 6)} onOpen={(e) => setOpen(e)} />}
      {tweaks.showMarquee && (
        <Marquee items={marqueeItems} speed="normal" />
      )}

      {/* Filter bar — sticky */}
      <section style={{ position: 'sticky', top: 71, zIndex: 30, background: 'rgba(7,6,12,0.92)', backdropFilter: 'blur(18px)', borderBottom: '1px solid var(--line)', padding: '20px 48px' }}>
        <div style={{ maxWidth: 1480, margin: '0 auto', display: 'flex', alignItems: 'center', gap: 24, flexWrap: 'wrap' }}>
          <span className="mono" style={{ fontSize: 11, color: 'var(--ink-dim)' }}>{filtered.length} eventos</span>
          <ChipBar items={window.CATEGORIAS} active={category} onClick={setCategory} />
          {mood && (
            <button className="chip active" onClick={() => setMood(null)} style={{ marginLeft: 'auto' }}>
              {window.MOODS.find(m => m.id === mood)?.emoji} {window.MOODS.find(m => m.id === mood)?.label} ×
            </button>
          )}
        </div>
      </section>

      <Carousel
        eventos={filtered.length ? filtered : otros}
        onOpen={(e) => setOpen(e)}
        kicker="Top picks · curados por VIBEZ"
        title={mood ? "Esto te va" : "Lo que rompe"}
        subtitle="Esta semana — selección editorial"
      />

      <MoodSelector moods={window.MOODS} selected={mood} onSelect={setMood} />

      {tweaks.showMap && (
        <MapEventos eventos={window.EVENTOS_DATA} onOpen={(e) => setOpen(e)} />
      )}

      {/* CTA strip */}
      <section style={{ padding: '120px 48px 40px', maxWidth: 1480, margin: '0 auto', textAlign: 'center', borderTop: '1px solid var(--line)', marginTop: 100 }}>
        <div className="mono" style={{ fontSize: 11, color: 'var(--magenta)', marginBottom: 18 }}>
          ¿Organizas eventos?
        </div>
        <h2 className="display" style={{ fontSize: 'clamp(56px, 9vw, 160px)', margin: 0, lineHeight: 0.85 }}>
          Pon tu sala<br/><em style={{ fontStyle: 'italic', color: 'var(--magenta)', fontFamily: '"Bebas Neue", sans-serif' }}>en el mapa</em>.
        </h2>
        <p style={{ fontFamily: 'Archivo Narrow', fontSize: 18, color: 'var(--ink-dim)', maxWidth: 560, margin: '24px auto 32px', textTransform: 'uppercase', letterSpacing: '0.08em' }}>
          Crea tu cuenta de empresa, publica eventos, vende entradas con QR, y recluta staff. Sin comisión los primeros 30 días.
        </p>
        <button className="btn-primary" style={{ padding: '20px 40px', borderRadius: 999, fontSize: 18 }}>
          Soy promotor →
        </button>
      </section>

      <VibezFooter />

      <DetailModal evento={open} onClose={() => setOpen(null)} onBuy={() => handleBuy(open)} />

      {toast && <div className="toast">{toast}</div>}

      {/* TWEAKS PANEL */}
      <TweaksPanel title="Tweaks">
        <TweakSection title="Estado">
          <TweakToggle label="Usuario logueado" value={tweaks.loggedIn} onChange={v => setTweak('loggedIn', v)} />
        </TweakSection>

        <TweakSection title="Dirección visual">
          <TweakSelect
            label="Aesthetic"
            value={tweaks.aesthetic}
            onChange={v => setTweak('aesthetic', v)}
            options={[
              { value: 'italo', label: 'Lila VIBEZ (brand)' },
              { value: 'electric', label: 'Electric · cian' },
              { value: 'acid', label: 'Acid · amarillo' }
            ]}
          />
        </TweakSection>

        <TweakSection title="Secciones">
          <TweakToggle label="Marquee/Ticker" value={tweaks.showMarquee} onChange={v => setTweak('showMarquee', v)} />
          <TweakToggle label="Mapa de la ciudad" value={tweaks.showMap} onChange={v => setTweak('showMap', v)} />
        </TweakSection>
      </TweaksPanel>
    </>
  );
}

const root = ReactDOM.createRoot(document.getElementById('app'));
root.render(<App />);

// Map aesthetic value (hex of first color) to data attribute
const aestheticMap = {
  '#ff1f7a': 'italo',
  '#00ffd1': 'electric',
  '#d4ff00': 'acid'
};
// Watch aesthetic
const obs = new MutationObserver(() => {
  // handled in App effect
});
