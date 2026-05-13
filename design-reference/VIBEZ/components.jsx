/* VIBEZ — Components: Nav, Hero, Marquee, Carousel, Map, Mood, Modal, Footer */

const { useState, useEffect, useRef, useMemo } = React;

// ════════════════════ NAV ════════════════════
function VibezNav({ onLogin, user }) {
  const [scrolled, setScrolled] = React.useState(false);
  const [menuOpen, setMenuOpen] = React.useState(false);
  React.useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 12);
    window.addEventListener('scroll', onScroll);
    return () => window.removeEventListener('scroll', onScroll);
  }, []);
  return (
    <header style={{
      position: 'sticky', top: 0, zIndex: 50,
      background: scrolled ? 'rgba(7,6,12,0.85)' : 'transparent',
      backdropFilter: scrolled ? 'blur(18px)' : 'none',
      borderBottom: scrolled ? '1px solid var(--line)' : '1px solid transparent',
      transition: 'all 0.3s ease',
    }}>
      <div style={{ maxWidth: 1480, margin: '0 auto', padding: '18px 32px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 24 }}>
        <a href="#" style={{ display: 'flex', alignItems: 'center', gap: 12, textDecoration: 'none', color: 'var(--ink)', position: 'relative' }}>
          <div style={{
            position: 'relative',
            padding: '6px 14px 6px 6px',
            display: 'flex', alignItems: 'center', gap: 10,
            background: 'linear-gradient(135deg, rgba(168,85,247,0.18), rgba(124,58,237,0.08))',
            border: '1px solid rgba(168,85,247,0.45)',
            borderRadius: 999,
            boxShadow: '0 0 24px rgba(168,85,247,0.35), inset 0 0 12px rgba(168,85,247,0.12)'
          }}>
            <img src="assets/logo_vibez.png" alt="VIBEZ" style={{ height: 44, width: 44, objectFit: 'contain', filter: 'drop-shadow(0 0 12px rgba(168,85,247,0.7))' }} />
            <span className="display" style={{ fontSize: 24, letterSpacing: '0.04em', color: 'var(--ink)', textShadow: '0 0 18px rgba(168,85,247,0.6)' }}>
              VIBEZ
            </span>
          </div>
        </a>
        <nav style={{ display: 'flex', gap: 28 }}>
          {(user ? ['Para ti', 'Mis tickets', 'Esta noche', 'Bolsa', 'Social'] : ['Explorar', 'Esta noche', 'Bolsa de trabajo', 'Cupones', 'Social']).map((l, i) => (
            <a key={i} href="#" className="mono" style={{
              fontSize: 11, color: i === 0 ? 'var(--ink)' : 'var(--ink-dim)',
              textDecoration: 'none', position: 'relative', paddingBottom: 4,
              borderBottom: i === 0 ? '1.5px solid var(--magenta)' : '1.5px solid transparent'
            }}>{l}</a>
          ))}
        </nav>
        {user ? (
          <div style={{ display: 'flex', alignItems: 'center', gap: 12, position: 'relative' }}>
            <button onClick={() => onLogin && onLogin('Notificaciones')} style={{ width: 38, height: 38, borderRadius: '50%', background: 'rgba(245,241,234,0.04)', border: '1px solid var(--ink-faint)', color: 'var(--ink)', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', position: 'relative' }}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <span style={{ position: 'absolute', top: 6, right: 6, width: 8, height: 8, borderRadius: '50%', background: 'var(--magenta)', boxShadow: '0 0 8px rgba(168,85,247,0.7)' }}></span>
            </button>
            <button onClick={() => setMenuOpen(!menuOpen)} style={{ display: 'flex', alignItems: 'center', gap: 10, background: 'rgba(168,85,247,0.08)', border: '1px solid rgba(168,85,247,0.4)', borderRadius: 999, padding: '4px 14px 4px 4px', cursor: 'pointer', color: 'var(--ink)' }}>
              <span style={{ width: 32, height: 32, borderRadius: '50%', background: 'linear-gradient(135deg, var(--morado), var(--magenta))', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Anton', fontSize: 13, color: 'var(--cream)' }}>{user.initials}</span>
              <span className="mono" style={{ fontSize: 11 }}>{user.name}</span>
            </button>
            {menuOpen && (
              <div style={{ position: 'absolute', top: 'calc(100% + 10px)', right: 0, background: 'rgba(13,10,24,0.95)', backdropFilter: 'blur(20px)', border: '1px solid var(--line)', borderRadius: 14, padding: 8, minWidth: 220, boxShadow: '0 20px 50px rgba(0,0,0,0.5)' }}>
                {['Mi perfil', 'Mis tickets', 'Favoritos', 'Cupones', 'Configuración', '— Cerrar sesión'].map((it, i) => (
                  <a key={i} href="#" onClick={(e) => { e.preventDefault(); setMenuOpen(false); onLogin && onLogin(it); }} style={{ display: 'block', padding: '10px 14px', color: i === 5 ? 'var(--magenta)' : 'var(--ink)', textDecoration: 'none', fontSize: 13, borderRadius: 8, fontFamily: 'Archivo Narrow', textTransform: 'uppercase', letterSpacing: '0.08em' }}>{it}</a>
                ))}
              </div>
            )}
          </div>
        ) : (
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            <button className="mono" style={{
              background: 'transparent', border: '1px solid var(--ink-faint)', color: 'var(--ink)',
              padding: '9px 18px', borderRadius: 999, fontSize: 11, cursor: 'pointer'
            }} onClick={onLogin}>Entrar</button>
            <button className="btn-primary" style={{ padding: '10px 20px', borderRadius: 999, fontSize: 13 }}>Registro</button>
          </div>
        )}
      </div>
    </header>
  );
}

// ════════════════════ MARQUEE ════════════════════
function Marquee({ items, speed = 'normal', reverse = false }) {
  const cls = `marquee-track ${speed === 'fast' ? 'fast' : ''} ${reverse ? 'reverse' : ''}`;
  const repeated = [...items, ...items, ...items];
  return (
    <div style={{ overflow: 'hidden', borderTop: '1px solid var(--line)', borderBottom: '1px solid var(--line)', padding: '18px 0' }}>
      <div className={cls}>
        {repeated.map((t, i) => (
          <span key={i} className="display" style={{ fontSize: 'clamp(36px, 5vw, 72px)', color: 'var(--ink)', opacity: i % 3 === 1 ? 1 : 0.45, display: 'inline-flex', alignItems: 'center', gap: 32 }}>
            {t}
            <span style={{ color: 'var(--magenta)', fontSize: '0.6em' }}>✦</span>
          </span>
        ))}
      </div>
    </div>
  );
}

// ════════════════════ HERO POSTER (full-bleed) ════════════════════
function HeroPoster({ evento, onOpen }) {
  const [scrollY, setScrollY] = useState(0);
  useEffect(() => {
    const onScroll = () => setScrollY(window.scrollY);
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  // Countdown
  const [now, setNow] = useState(Date.now());
  useEffect(() => {
    const id = setInterval(() => setNow(Date.now()), 1000);
    return () => clearInterval(id);
  }, []);
  const target = new Date(evento.fecha).getTime();
  const diff = Math.max(0, target - now);
  const days = Math.floor(diff / (1000 * 60 * 60 * 24));
  const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
  const minutes = Math.floor((diff / (1000 * 60)) % 60);
  const seconds = Math.floor((diff / 1000) % 60);

  return (
    <section className="hero-poster" style={{ position: 'relative', minHeight: '92vh', overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
      {/* Full-bleed image */}
      <div style={{ position: 'absolute', inset: 0, overflow: 'hidden' }}>
        <img
          className="parallax-img"
          src={evento.img}
          alt={evento.titulo}
          style={{
            width: '100%', height: '115%', objectFit: 'cover',
            transform: `translateY(${scrollY * 0.25}px) scale(1.05)`,
            filter: 'contrast(1.05) saturate(1.15) brightness(0.78)'
          }}
        />
        {/* Magenta wash + dark gradient */}
        <div style={{
          position: 'absolute', inset: 0,
          background: 'linear-gradient(180deg, rgba(7,6,12,0.4) 0%, rgba(7,6,12,0.1) 30%, rgba(7,6,12,0.6) 75%, rgba(7,6,12,0.95) 100%)'
        }} />
        <div style={{
          position: 'absolute', inset: 0,
          background: 'radial-gradient(ellipse at 80% 20%, rgba(255,31,122,0.35) 0%, transparent 50%)',
          mixBlendMode: 'screen'
        }} />
      </div>

      {/* Top badge row */}
      <div style={{ position: 'relative', zIndex: 5, padding: '32px 48px 0', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <div className="mono" style={{ fontSize: 11, color: 'var(--ink-dim)', display: 'flex', alignItems: 'center', gap: 10 }}>
          <span className="pulse-dot" style={{ width: 8, height: 8, borderRadius: '50%', background: 'var(--magenta)', display: 'inline-block' }}></span>
          En vivo · {evento.ciudad}
        </div>
        <div className="mono" style={{ fontSize: 11, color: 'var(--ink-dim)' }}>
          Edición #428 · Mayo 2026
        </div>
      </div>

      {/* Center content */}
      <div style={{ position: 'relative', zIndex: 5, flex: 1, display: 'flex', flexDirection: 'column', justifyContent: 'flex-end', padding: '0 48px 56px', maxWidth: 1480, width: '100%', margin: '0 auto' }}>
        <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', gap: 48, flexWrap: 'wrap' }}>

          <div style={{ flex: '1 1 600px', maxWidth: 900 }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 28 }}>
              <span className="sticker" style={{ fontSize: 14, padding: '6px 14px' }}>★ FEATURED · Esta noche</span>
              <span className="mono" style={{ fontSize: 11, color: 'var(--ink-dim)' }}>{evento.categoria}</span>
            </div>

            <h1 className="display glow-magenta" style={{
              fontSize: 'clamp(64px, 11vw, 188px)',
              margin: 0,
              color: 'var(--ink)',
            }}>
              {evento.titulo.split(' × ').map((part, i, arr) => (
                <React.Fragment key={i}>
                  <span style={{ display: 'block' }}>
                    {i === 1 ? <em style={{ fontStyle: 'italic', color: 'var(--magenta)', fontFamily: '"Bebas Neue", sans-serif' }}>{part}</em> : part}
                  </span>
                </React.Fragment>
              ))}
            </h1>

            <p style={{ fontFamily: '"Archivo Narrow", sans-serif', fontSize: 22, color: 'var(--cream)', margin: '20px 0 6px', fontStyle: 'italic', maxWidth: 540 }}>
              "{evento.tagline}"
            </p>
            <p className="mono" style={{ fontSize: 12, color: 'var(--ink-dim)', margin: 0 }}>
              feat. {evento.artista}
            </p>

            <div style={{ display: 'flex', gap: 14, marginTop: 36, flexWrap: 'wrap' }}>
              <button className="btn-primary" style={{ padding: '18px 36px', borderRadius: 999, fontSize: 18 }} onClick={() => onOpen(evento)}>
                Comprar entrada · {evento.precio}
              </button>
              <button className="btn-ghost" style={{ padding: '18px 28px', borderRadius: 999, fontSize: 14 }}>
                ♡ Guardar
              </button>
            </div>
          </div>

          {/* Right column: countdown + meta */}
          <div style={{ flex: '0 0 320px', display: 'flex', flexDirection: 'column', gap: 24 }}>
            <div>
              <div className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)', marginBottom: 10 }}>Empieza en</div>
              <div style={{ display: 'flex', gap: 8 }}>
                {[
                  { v: days, l: 'días' },
                  { v: hours, l: 'h' },
                  { v: minutes, l: 'min' },
                  { v: seconds, l: 'seg' }
                ].map((it, i) => (
                  <div key={i} style={{ flex: 1, padding: '14px 6px', textAlign: 'center', background: 'rgba(7,6,12,0.5)', border: '1px solid var(--line)', backdropFilter: 'blur(8px)' }}>
                    <div className="display" style={{ fontSize: 32, color: i === 3 ? 'var(--magenta)' : 'var(--ink)' }}>
                      {String(it.v).padStart(2, '0')}
                    </div>
                    <div className="mono" style={{ fontSize: 9, color: 'var(--ink-dim)', marginTop: 4 }}>{it.l}</div>
                  </div>
                ))}
              </div>
            </div>

            <div style={{ borderTop: '1px solid var(--line)', paddingTop: 16, display: 'flex', flexDirection: 'column', gap: 10 }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)' }}>Fecha</span>
                <span style={{ fontSize: 14, fontWeight: 600 }}>{evento.fechaFmt}</span>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)' }}>Horario</span>
                <span style={{ fontSize: 14, fontWeight: 600 }}>{evento.hora}</span>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)' }}>Sala</span>
                <span style={{ fontSize: 14, fontWeight: 600 }}>{evento.lugar}</span>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)' }}>Cupos</span>
                <span style={{ fontSize: 14, fontWeight: 600, color: evento.cupos < 50 ? 'var(--magenta)' : 'var(--ink)' }}>
                  {evento.cupos < 50 ? `Quedan ${evento.cupos}` : '+ ' + evento.cupos}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Vertical text deco */}
      <div className="mono vertical-text" style={{ position: 'absolute', right: 16, top: '50%', fontSize: 10, color: 'var(--ink-dim)', letterSpacing: '0.3em' }}>
        VIBEZ · NIGHT EDITION 26 · BCN
      </div>
    </section>
  );
}

// ════════════════════ CHIPS / FILTERS ════════════════════
function ChipBar({ items, active, onClick }) {
  return (
    <div className="no-scrollbar" style={{ overflowX: 'auto', whiteSpace: 'nowrap', padding: '4px 0' }}>
      <div style={{ display: 'inline-flex', gap: 10 }}>
        {items.map(it => (
          <button key={it} className={`chip ${active === it ? 'active' : ''}`} onClick={() => onClick(it)}>
            {it}
          </button>
        ))}
      </div>
    </div>
  );
}

// ════════════════════ EVENT CARD (carousel) ════════════════════
function EventCard({ evento, idx, onOpen, big }) {
  const [fav, setFav] = useState(false);
  return (
    <article className="vibe-card" style={{
      flex: big ? '0 0 540px' : '0 0 360px',
      minWidth: big ? 540 : 360,
      cursor: 'pointer'
    }} onClick={() => onOpen(evento)}>
      <div className="img-wrap" style={{ position: 'relative', aspectRatio: big ? '4/5' : '3/4', overflow: 'hidden' }}>
        <img src={evento.img} alt={evento.titulo} style={{ width: '100%', height: '100%', objectFit: 'cover', filter: 'contrast(1.05) saturate(1.1) brightness(0.85)' }} />
        <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(180deg, transparent 50%, rgba(7,6,12,0.85) 100%)' }} />

        {/* Index */}
        <div className="num-big" style={{ position: 'absolute', top: 12, left: 16, fontSize: big ? 96 : 72 }}>
          {String(idx + 1).padStart(2, '0')}
        </div>

        {/* Sold out badge */}
        {evento.soldOut && (
          <div style={{ position: 'absolute', top: 18, right: 18, background: 'var(--cream)', color: 'var(--bg)', padding: '4px 12px', fontFamily: 'Anton', fontSize: 11, letterSpacing: '0.05em', transform: 'rotate(4deg)' }}>
            SOLD OUT
          </div>
        )}

        {/* Favorite */}
        <button onClick={(e) => { e.stopPropagation(); setFav(!fav); }} style={{
          position: 'absolute', top: 18, right: evento.soldOut ? 90 : 18,
          width: 38, height: 38, borderRadius: '50%',
          background: fav ? 'var(--magenta)' : 'rgba(7,6,12,0.55)',
          border: '1px solid var(--ink-faint)',
          color: 'var(--ink)', backdropFilter: 'blur(10px)', cursor: 'pointer',
          display: 'flex', alignItems: 'center', justifyContent: 'center'
        }}>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </button>

        {/* Bottom info */}
        <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, padding: 20 }}>
          <div className="mono" style={{ fontSize: 10, color: 'var(--magenta-2)', marginBottom: 8, display: 'flex', justifyContent: 'space-between' }}>
            <span>{evento.fechaFmt} · {evento.categoria}</span>
            <span>{evento.precio}</span>
          </div>
          <h3 className="display" style={{ fontSize: big ? 44 : 30, margin: 0, lineHeight: 0.95, color: 'var(--ink)' }}>
            {evento.titulo}
          </h3>
          <p style={{ fontFamily: 'Archivo Narrow', fontSize: 13, color: 'var(--ink-dim)', margin: '10px 0 0', textTransform: 'uppercase', letterSpacing: '0.08em' }}>
            {evento.lugar}
          </p>
        </div>
      </div>
    </article>
  );
}

// ════════════════════ HORIZONTAL CAROUSEL ════════════════════
function Carousel({ eventos, onOpen, title, subtitle, kicker }) {
  const ref = useRef(null);
  const scroll = (dir) => {
    if (!ref.current) return;
    ref.current.scrollBy({ left: dir * 420, behavior: 'smooth' });
  };
  return (
    <section style={{ padding: '90px 48px 0', maxWidth: 1480, margin: '0 auto' }}>
      <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 36, gap: 20, flexWrap: 'wrap' }}>
        <div>
          {kicker && (
            <div className="mono" style={{ fontSize: 11, color: 'var(--magenta)', marginBottom: 12, display: 'flex', alignItems: 'center', gap: 10 }}>
              <span style={{ width: 28, height: 1, background: 'var(--magenta)', display: 'inline-block' }}></span>
              {kicker}
            </div>
          )}
          <h2 className="display" style={{ fontSize: 'clamp(48px, 6vw, 96px)', margin: 0, color: 'var(--ink)' }}>{title}</h2>
          {subtitle && <p style={{ fontFamily: 'Archivo Narrow', fontSize: 16, color: 'var(--ink-dim)', margin: '12px 0 0', textTransform: 'uppercase', letterSpacing: '0.1em' }}>{subtitle}</p>}
        </div>
        <div style={{ display: 'flex', gap: 8 }}>
          <button onClick={() => scroll(-1)} style={{ width: 48, height: 48, borderRadius: '50%', border: '1px solid var(--ink-faint)', background: 'transparent', color: 'var(--ink)', cursor: 'pointer' }}>←</button>
          <button onClick={() => scroll(1)} style={{ width: 48, height: 48, borderRadius: '50%', border: '1px solid var(--ink-faint)', background: 'var(--magenta)', color: 'var(--cream)', cursor: 'pointer' }}>→</button>
        </div>
      </div>
      <div ref={ref} className="scroll-x no-scrollbar cards-row" style={{ display: 'flex', gap: 20, overflowX: 'auto', paddingBottom: 16 }}>
        {eventos.map((e, i) => (
          <EventCard key={e.id} evento={e} idx={i} onOpen={onOpen} />
        ))}
      </div>
    </section>
  );
}

// ════════════════════ MOOD SELECTOR ════════════════════
function MoodSelector({ moods, selected, onSelect }) {
  return (
    <section style={{ padding: '90px 48px 0', maxWidth: 1480, margin: '0 auto' }}>
      <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 36, gap: 24, flexWrap: 'wrap' }}>
        <div>
          <div className="mono" style={{ fontSize: 11, color: 'var(--magenta)', marginBottom: 12 }}>
            <span style={{ width: 28, height: 1, background: 'var(--magenta)', display: 'inline-block', marginRight: 10 }}></span>
            ¿Qué te apetece esta noche?
          </div>
          <h2 className="display" style={{ fontSize: 'clamp(48px, 6vw, 96px)', margin: 0 }}>
            Pick your <em style={{ fontStyle: 'italic', color: 'var(--magenta)', fontFamily: '"Bebas Neue", sans-serif' }}>mood</em>.
          </h2>
        </div>
      </div>
      <div className="mood-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: 14 }}>
        {moods.map(m => (
          <button key={m.id} className={`mood-card ${selected === m.id ? 'selected' : ''}`} onClick={() => onSelect(m.id === selected ? null : m.id)} style={{
            background: 'transparent', color: 'var(--ink)',
            padding: '28px 22px', textAlign: 'left', cursor: 'pointer',
            display: 'flex', flexDirection: 'column', gap: 14,
            minHeight: 160
          }}>
            <span style={{ fontSize: 38 }}>{m.emoji}</span>
            <div className="display" style={{ fontSize: 22, lineHeight: 1 }}>{m.label}</div>
          </button>
        ))}
      </div>
    </section>
  );
}

// ════════════════════ MAP ════════════════════
function MapEventos({ eventos, onOpen }) {
  const ref = useRef(null);
  const mapRef = useRef(null);
  useEffect(() => {
    if (!ref.current || mapRef.current) return;
    const map = L.map(ref.current, {
      center: [41.385, 2.176],
      zoom: 13,
      zoomControl: false,
      scrollWheelZoom: false
    });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    mapRef.current = map;

    eventos.forEach(e => {
      const icon = L.divIcon({
        className: '',
        html: `<div class="vibez-pin ${e.featured ? 'featured' : ''}">${e.featured ? '★' : ''}</div>`,
        iconSize: [38, 38],
        iconAnchor: [19, 19]
      });
      const m = L.marker(e.coords, { icon }).addTo(map);
      m.on('click', () => onOpen(e));
    });
    return () => { map.remove(); mapRef.current = null; };
  }, []);

  return (
    <section style={{ padding: '90px 48px 0', maxWidth: 1480, margin: '0 auto' }}>
      <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 36, gap: 20, flexWrap: 'wrap' }}>
        <div>
          <div className="mono" style={{ fontSize: 11, color: 'var(--magenta)', marginBottom: 12 }}>
            <span style={{ width: 28, height: 1, background: 'var(--magenta)', display: 'inline-block', marginRight: 10 }}></span>
            La ciudad ardiendo
          </div>
          <h2 className="display" style={{ fontSize: 'clamp(48px, 6vw, 96px)', margin: 0 }}>
            BCN <em style={{ fontStyle: 'italic', color: 'var(--magenta)', fontFamily: '"Bebas Neue", sans-serif' }}>en llamas</em>
          </h2>
          <p style={{ fontFamily: 'Archivo Narrow', fontSize: 14, color: 'var(--ink-dim)', margin: '12px 0 0', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
            {eventos.length} eventos activos · radio 8 km
          </p>
        </div>
        <div style={{ display: 'flex', gap: 8 }}>
          <button className="chip active">Esta noche</button>
          <button className="chip">Este finde</button>
          <button className="chip">Próximos 30 días</button>
        </div>
      </div>
      <div style={{ position: 'relative', height: 560, border: '1px solid var(--line)', overflow: 'hidden' }}>
        <div ref={ref} style={{ width: '100%', height: '100%' }}></div>
        {/* legend */}
        <div style={{ position: 'absolute', top: 20, left: 20, background: 'rgba(7,6,12,0.9)', backdropFilter: 'blur(10px)', padding: 16, border: '1px solid var(--line)', maxWidth: 240 }}>
          <div className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)', marginBottom: 10 }}>LEYENDA</div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 8 }}>
            <span style={{ width: 14, height: 14, borderRadius: '50%', background: 'linear-gradient(135deg, var(--magenta), var(--morado))' }}></span>
            <span style={{ fontSize: 12 }}>Evento</span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
            <span style={{ width: 18, height: 18, borderRadius: '50%', background: 'linear-gradient(135deg, var(--magenta), var(--morado))', boxShadow: '0 0 14px var(--magenta)' }}></span>
            <span style={{ fontSize: 12 }}>Featured · Esta noche</span>
          </div>
        </div>
      </div>
    </section>
  );
}

// ════════════════════ DETAIL MODAL ════════════════════
function DetailModal({ evento, onClose, onBuy }) {
  if (!evento) return null;
  return (
    <div className="modal-back" onClick={onClose}>
      <div className="modal-card detail-modal" onClick={e => e.stopPropagation()} style={{
        position: 'fixed', inset: '5% 8%', background: 'var(--bg)',
        border: '1px solid var(--line)', overflow: 'auto', display: 'grid',
        gridTemplateColumns: '1.2fr 1fr'
      }}>
        <div style={{ position: 'relative', overflow: 'hidden', minHeight: 520 }}>
          <img src={evento.img} alt={evento.titulo} style={{ width: '100%', height: '100%', objectFit: 'cover', filter: 'contrast(1.05) saturate(1.1) brightness(0.85)' }} />
          <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(180deg, transparent 50%, rgba(7,6,12,0.7))' }} />
          <button onClick={onClose} style={{ position: 'absolute', top: 20, right: 20, width: 44, height: 44, borderRadius: '50%', background: 'rgba(7,6,12,0.6)', border: '1px solid var(--ink-faint)', color: 'var(--ink)', cursor: 'pointer', backdropFilter: 'blur(10px)', fontSize: 18 }}>×</button>
          <div style={{ position: 'absolute', bottom: 28, left: 28, right: 28 }}>
            <span className="sticker">{evento.categoria}</span>
            <h2 className="display" style={{ fontSize: 'clamp(48px, 6vw, 88px)', margin: '20px 0 0', color: 'var(--ink)' }}>
              {evento.titulo}
            </h2>
          </div>
        </div>
        <div style={{ padding: 48, display: 'flex', flexDirection: 'column', gap: 24 }}>
          <div>
            <div className="mono" style={{ fontSize: 11, color: 'var(--ink-dim)', marginBottom: 8 }}>Artistas</div>
            <p style={{ fontSize: 18, margin: 0 }}>{evento.artista}</p>
          </div>
          <p style={{ fontFamily: 'Archivo Narrow', fontSize: 18, color: 'var(--cream)', fontStyle: 'italic', margin: 0, borderLeft: '2px solid var(--magenta)', paddingLeft: 14 }}>
            "{evento.tagline}"
          </p>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14, paddingTop: 16, borderTop: '1px solid var(--line)' }}>
            {[
              ['Fecha', evento.fechaFmt],
              ['Hora', evento.hora],
              ['Sala', evento.lugar],
              ['Ciudad', evento.ciudad],
            ].map(([l, v]) => (
              <div key={l}>
                <div className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)', marginBottom: 4 }}>{l}</div>
                <div style={{ fontSize: 14, fontWeight: 600 }}>{v}</div>
              </div>
            ))}
          </div>
          <div style={{ marginTop: 'auto', paddingTop: 24, borderTop: '1px solid var(--line)' }}>
            <div style={{ display: 'flex', alignItems: 'baseline', justifyContent: 'space-between', marginBottom: 16 }}>
              <span className="mono" style={{ fontSize: 11, color: 'var(--ink-dim)' }}>Desde</span>
              <span className="display" style={{ fontSize: 56, color: 'var(--magenta)' }}>{evento.precio}</span>
            </div>
            <button className="btn-primary" style={{ width: '100%', padding: '20px', fontSize: 18, borderRadius: 999 }} onClick={onBuy} disabled={evento.soldOut}>
              {evento.soldOut ? 'Lista de espera' : 'Comprar entrada →'}
            </button>
            <button className="btn-ghost" style={{ width: '100%', padding: '14px', fontSize: 13, borderRadius: 999, marginTop: 10 }}>
              ♡ Guardar para luego
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}

// ════════════════════ FOOTER ════════════════════
function VibezFooter() {
  return (
    <footer style={{ marginTop: 100, padding: '60px 48px 36px', borderTop: '1px solid var(--line)' }}>
      <div style={{ maxWidth: 1480, margin: '0 auto' }}>
        <div className="display" style={{ fontSize: 'clamp(80px, 14vw, 240px)', lineHeight: 0.85, color: 'transparent', WebkitTextStroke: '1.5px var(--ink-faint)' }}>
          VIBEZ ✦ <em style={{ fontStyle: 'italic', color: 'var(--magenta)', WebkitTextStroke: 0, fontFamily: '"Bebas Neue", sans-serif' }}>NIGHTS</em>
        </div>
        <div style={{ marginTop: 60, display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))', gap: 32, paddingTop: 32, borderTop: '1px solid var(--line)' }}>
          {[
            ['Plataforma', ['Explorar', 'Esta noche', 'Cupones', 'Bolsa de trabajo']],
            ['Para empresas', ['Crear evento', 'Panel empresa', 'Publicar oferta', 'Pricing']],
            ['Vibez', ['Quiénes somos', 'Manifiesto', 'Prensa', 'Contacto']],
            ['Legal', ['Privacidad', 'Cookies', 'Términos', 'Devoluciones']]
          ].map(([title, items]) => (
            <div key={title}>
              <div className="mono" style={{ fontSize: 10, color: 'var(--magenta)', marginBottom: 14 }}>{title}</div>
              {items.map(it => (
                <a key={it} href="#" style={{ display: 'block', fontSize: 13, color: 'var(--ink-dim)', textDecoration: 'none', padding: '4px 0' }}>{it}</a>
              ))}
            </div>
          ))}
        </div>
        <div style={{ marginTop: 40, paddingTop: 24, borderTop: '1px solid var(--line)', display: 'flex', justifyContent: 'space-between', flexWrap: 'wrap', gap: 16 }}>
          <span className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)' }}>© 2026 VIBEZ · BCN · MAD · LIS</span>
          <span className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)' }}>Made for the night</span>
        </div>
      </div>
    </footer>
  );
}

// ════════════════════ LOGGED-IN BLOCKS ════════════════════
function LoggedHero({ user, onOpen, eventos }) {
  const next = eventos[0];
  return (
    <section style={{ padding: '60px 48px 40px', maxWidth: 1480, margin: '0 auto', position: 'relative' }}>
      <div style={{ display: 'grid', gridTemplateColumns: '1.4fr 1fr', gap: 32 }} className="logged-hero-grid">
        <div>
          <div className="mono" style={{ fontSize: 11, color: 'var(--magenta)', marginBottom: 14, display: 'flex', alignItems: 'center', gap: 10 }}>
            <span style={{ width: 28, height: 1, background: 'var(--magenta)', display: 'inline-block' }}></span>
            Hola de nuevo, {user.firstName}
          </div>
          <h1 className="display glow-magenta" style={{ fontSize: 'clamp(56px, 9vw, 132px)', margin: 0, lineHeight: 0.9 }}>
            Tu próxima<br/><em style={{ fontStyle: 'italic', color: 'var(--magenta)', fontFamily: '"Bebas Neue", sans-serif' }}>fiesta</em> empieza ya.
          </h1>
          <p style={{ fontFamily: 'Archivo Narrow', fontSize: 18, color: 'var(--ink-dim)', maxWidth: 540, margin: '24px 0 28px', textTransform: 'uppercase', letterSpacing: '0.08em', lineHeight: 1.5 }}>
            Tienes <strong style={{ color: 'var(--ink)' }}>{user.tickets} tickets activos</strong> · seguiste a <strong style={{ color: 'var(--ink)' }}>{user.following} promotores</strong> · {user.points} puntos VIBEZ
          </p>
          <div style={{ display: 'flex', gap: 12, flexWrap: 'wrap' }}>
            <button className="btn-primary" style={{ padding: '16px 28px', borderRadius: 999, fontSize: 15 }}>Mis tickets →</button>
            <button className="btn-ghost" style={{ padding: '16px 24px', borderRadius: 999, fontSize: 13 }}>Cupones ({user.coupons})</button>
          </div>
        </div>

        {next && (
          <div onClick={() => onOpen(next)} style={{
            position: 'relative', borderRadius: 18, overflow: 'hidden', cursor: 'pointer',
            border: '1px solid rgba(168,85,247,0.3)', minHeight: 320,
            boxShadow: '0 20px 50px rgba(0,0,0,0.4), 0 0 30px rgba(168,85,247,0.15)'
          }}>
            <img src={next.img} alt="" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover', filter: 'contrast(1.05) brightness(0.6)' }}/>
            <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(180deg, rgba(7,6,12,0.2) 0%, rgba(7,6,12,0.95) 100%)' }}/>
            <div style={{ position: 'relative', padding: 24, height: '100%', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', minHeight: 320 }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                <span className="mono" style={{ fontSize: 10, color: 'var(--magenta-2)', background: 'rgba(168,85,247,0.18)', border: '1px solid rgba(168,85,247,0.4)', padding: '4px 10px', borderRadius: 999 }}>★ TU PRÓXIMO EVENTO</span>
                <span className="mono" style={{ fontSize: 10, color: 'var(--ink-dim)' }}>{next.fechaFmt}</span>
              </div>
              <div>
                <h3 className="display" style={{ fontSize: 32, margin: '0 0 6px', lineHeight: 0.95 }}>{next.titulo}</h3>
                <p className="mono" style={{ fontSize: 11, color: 'var(--ink-dim)', margin: 0 }}>{next.lugar} · {next.hora}</p>
              </div>
            </div>
          </div>
        )}
      </div>
    </section>
  );
}

function MisTickets({ tickets, onOpen }) {
  return (
    <section style={{ padding: '60px 48px 40px', maxWidth: 1480, margin: '0 auto' }}>
      <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 28, gap: 20, flexWrap: 'wrap' }}>
        <div>
          <div className="mono" style={{ fontSize: 11, color: 'var(--magenta)', marginBottom: 12 }}>
            <span style={{ width: 28, height: 1, background: 'var(--magenta)', display: 'inline-block', marginRight: 10 }}></span>
            Mis tickets · {tickets.length} activos
          </div>
          <h2 className="display" style={{ fontSize: 'clamp(40px, 6vw, 80px)', margin: 0 }}>
            Lista <em style={{ fontStyle: 'italic', color: 'var(--magenta)', fontFamily: '"Bebas Neue", sans-serif' }}>VIP</em>
          </h2>
        </div>
        <a href="#" className="mono" style={{ fontSize: 11, color: 'var(--magenta-2)', textDecoration: 'none', borderBottom: '1px solid currentColor' }}>Ver historial →</a>
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(320px, 1fr))', gap: 16 }} className="tickets-grid">
        {tickets.map((t, i) => (
          <div key={i} onClick={() => onOpen(t.evento)} style={{
            display: 'grid', gridTemplateColumns: '1fr 1px 90px',
            background: 'linear-gradient(135deg, rgba(168,85,247,0.08), rgba(13,10,24,0.7))',
            border: '1px solid rgba(168,85,247,0.3)', borderRadius: 14, overflow: 'hidden', cursor: 'pointer',
            position: 'relative', transition: 'all 0.25s ease'
          }} onMouseEnter={(e) => { e.currentTarget.style.transform='translateY(-3px)'; e.currentTarget.style.boxShadow='0 14px 30px rgba(168,85,247,0.3)'; }} onMouseLeave={(e) => { e.currentTarget.style.transform='none'; e.currentTarget.style.boxShadow='none'; }}>
            <div style={{ padding: '16px 18px' }}>
              <div className="mono" style={{ fontSize: 9, color: 'var(--magenta-2)', marginBottom: 6 }}>{t.evento.categoria} · {t.cantidad}× ENTRADA</div>
              <div className="display" style={{ fontSize: 18, lineHeight: 1, marginBottom: 8 }}>{t.evento.titulo}</div>
              <div style={{ fontSize: 11, color: 'var(--ink-dim)', fontFamily: 'Archivo Narrow', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
                {t.evento.fechaFmt} · {t.evento.hora}
              </div>
              <div style={{ fontSize: 11, color: 'var(--ink-dim)', fontFamily: 'Archivo Narrow', textTransform: 'uppercase', letterSpacing: '0.1em', marginTop: 2 }}>
                {t.evento.lugar}
              </div>
            </div>
            <div style={{ background: 'repeating-linear-gradient(0deg, var(--magenta) 0 4px, transparent 4px 8px)' }}/>
            <div style={{ padding: 12, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: 4 }}>
              <div style={{ width: 56, height: 56, background: 'var(--cream)', borderRadius: 6, padding: 4, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <svg width="48" height="48" viewBox="0 0 48 48"><rect width="48" height="48" fill="white"/><g fill="black">
                  {Array.from({length: 64}).map((_, k) => {
                    const r = (i * 31 + k * 7) % 100;
                    if (r < 50) return <rect key={k} x={(k%8)*6} y={Math.floor(k/8)*6} width="6" height="6"/>;
                    return null;
                  })}
                </g></svg>
              </div>
              <div className="mono" style={{ fontSize: 8, color: 'var(--ink-dim)' }}>#{t.id}</div>
            </div>
          </div>
        ))}
      </div>
    </section>
  );
}

function ParaTi({ user, eventos, onOpen }) {
  return (
    <section style={{ padding: '60px 48px 40px', maxWidth: 1480, margin: '0 auto' }}>
      <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 28, gap: 20, flexWrap: 'wrap' }}>
        <div>
          <div className="mono" style={{ fontSize: 11, color: 'var(--magenta)', marginBottom: 12 }}>
            <span style={{ width: 28, height: 1, background: 'var(--magenta)', display: 'inline-block', marginRight: 10 }}></span>
            Recomendado para {user.firstName}
          </div>
          <h2 className="display" style={{ fontSize: 'clamp(40px, 6vw, 80px)', margin: 0 }}>
            Te <em style={{ fontStyle: 'italic', color: 'var(--magenta)', fontFamily: '"Bebas Neue", sans-serif' }}>conocemos</em>.
          </h2>
          <p style={{ fontFamily: 'Archivo Narrow', fontSize: 14, color: 'var(--ink-dim)', margin: '10px 0 0', textTransform: 'uppercase', letterSpacing: '0.1em' }}>Basado en tu historial · techno · disco · BCN</p>
        </div>
      </div>
      <div className="cards-row no-scrollbar" style={{ display: 'flex', gap: 20, overflowX: 'auto', paddingBottom: 16 }}>
        {eventos.map((e, i) => (
          <article key={e.id} onClick={() => onOpen(e)} className="vibe-card" style={{
            flex: '0 0 320px', minWidth: 320, position: 'relative', cursor: 'pointer'
          }}>
            <div className="img-wrap" style={{ position: 'relative', aspectRatio: '3/4', overflow: 'hidden', borderRadius: 14 }}>
              <img src={e.img} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', filter: 'contrast(1.05) saturate(1.1) brightness(0.85)' }}/>
              <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(180deg, transparent 50%, rgba(7,6,12,0.9))' }}/>
              <div style={{ position: 'absolute', top: 12, left: 12, background: 'rgba(168,85,247,0.85)', color: 'var(--cream)', padding: '4px 10px', borderRadius: 999, fontFamily: 'Archivo Narrow', fontSize: 10, textTransform: 'uppercase', letterSpacing: '0.1em', fontWeight: 600 }}>
                {[97, 92, 88, 85, 82, 79, 76][i % 7]}% match
              </div>
              <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, padding: 18 }}>
                <div className="mono" style={{ fontSize: 9, color: 'var(--magenta-2)', marginBottom: 6 }}>{e.fechaFmt} · {e.precio}</div>
                <h3 className="display" style={{ fontSize: 22, margin: 0, lineHeight: 1 }}>{e.titulo}</h3>
                <p className="mono" style={{ fontSize: 9, color: 'var(--ink-dim)', margin: '6px 0 0' }}>{e.lugar}</p>
              </div>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}

// expose
Object.assign(window, { VibezNav, Marquee, HeroPoster, ChipBar, EventCard, Carousel, MoodSelector, MapEventos, DetailModal, VibezFooter, LoggedHero, MisTickets, ParaTi });
